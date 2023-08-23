export function initCssSelector() {
    Livewire.on('driverjs::change-css-selector-status', function ({enabled}) {

        if (enabled) {
            let lastMouseX = 0;
            let lastMouseY = 0;

            let cursor = document.querySelector('#circle-cursor');
            document.onmousemove = handleMouseMove;
            document.onkeyup = release;

            document.onmouseover = enterCursor;
            document.onmouseleave = leaveCursor;

            let active = false;
            let hasNavigator = window.navigator.clipboard;
            let isInElement = false;
            let selected = null;

            function release(event) {
                if (event.key !== 'Escape') return;
                active = false;
                selected = null;
                cursor.style.display = 'none';
            }

            document.addEventListener('keydown', function (event) {

                if (event.ctrlKey && event.code === 'Space' && !active) {
                    if (!hasNavigator) {
                        new FilamentNotification()
                            .title('Filament Tour - CSS Selector')
                            .body("Your browser does not support the Clipboard API !<br>Don't forget to be in <b>https://</b> protocol")
                            .danger()
                            .send();
                    } else {
                        active = true;
                        moveCursor(lastMouseX, lastMouseY);
                        cursor.style.display = 'block';

                        new FilamentNotification()
                            .title('Filament Tour - CSS Selector')
                            .body('Activated !<br>Press Ctrl + C to copy the CSS Selector of the selected element !')
                            .success()
                            .send();
                    }
                }

                if (event.ctrlKey && event.code === 'KeyC' && active) {
                    navigator.clipboard.writeText(getCssSelector(selected) ?? 'Nothing selected !');

                    active = false;
                    selected = null;
                    cursor.style.display = 'none';

                    new FilamentNotification()
                        .title('Filament Tour - CSS Selector')
                        .body(`CSS Selector copied to clipboard !`)
                        .success()
                        .send();
                }

            });

            function getCssSelector(element) {
                if (!(element instanceof Element)) return;
                let path = [];
                while (element.nodeType === Node.ELEMENT_NODE) {
                    let selector = element.nodeName.toLowerCase();
                    if (element.id) {
                        selector += '#' + element.id;
                        path.unshift(selector);
                        break;
                    } else {
                        let sib = element, nth = 1;
                        while (sib = sib.previousElementSibling) {
                            if (sib.nodeName.toLowerCase() === selector) nth++;
                        }
                        if (nth !== 1) selector += ":nth-of-type(" + nth + ")";
                    }
                    path.unshift(selector);
                    element = element.parentNode;
                }
                return path.join(" > ");
            }

            function handleMouseMove(event) {
                lastMouseX = event.clientX;
                lastMouseY = event.clientY;

                moveCursor(event.clientX, event.clientY);
            }

            function moveCursor(pX, pY) {
                if (!active) return;

                let diff = 10;
                if (!isInElement) {
                    cursor.style.left = (pX - diff) + 'px';
                    cursor.style.top = (pY - diff) + 'px';
                    cursor.style.width = '20px';
                    cursor.style.height = '20px';
                    cursor.style.borderRadius = "50%";
                }
            }


            function enterCursor(event) {
                event.stopPropagation();

                if (!active) return;

                isInElement = true;

                let elem = document.querySelector(getCssSelector(event.target));

                if (elem) {
                    let eX = elem.offsetParent ? elem.offsetLeft + elem.offsetParent.offsetLeft : elem.offsetLeft
                    let eY = elem.offsetParent ? elem.offsetTop + elem.offsetParent.offsetTop : elem.offsetTop;
                    let eW = elem.offsetWidth;
                    let eH = elem.offsetHeight;
                    let diff = 6;
                    selected = elem;
                    cursor.style.left = eX - diff + 'px';
                    cursor.style.top = eY - diff + 'px';
                    cursor.style.width = (eW + diff * 2 - 1) + 'px';
                    cursor.style.height = (eH + diff * 2 - 1) + 'px';
                    cursor.style.borderRadius = "5px";
                }
            }

            function leaveCursor(event) {
                if (!active) return;

                isInElement = false;
            }
        }
    });
}
