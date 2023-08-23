export function initCssSelector() {
    Livewire.on('filament-tour::change-css-selector-status', function ({enabled}) {

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
                    navigator.clipboard.writeText(getOptimizedSelector(selected) ?? 'Nothing selected !');

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

            function escapeCssSelector(str) {
                return str.replace(/([!"#$%&'()*+,./:;<=>?@[\]^`{|}~])/g, '\\$1');
            }

            function getOptimizedSelector(el) {
                let fullSelector = getCssSelector(el);

                return optimizeSelector(fullSelector);
            }

            function optimizeSelector(selector) {
                let parts = selector.split(' > ');

                for (let i = parts.length - 2; i >= 0; i--) {
                    let testSelector = parts.slice(i).join(' > ');
                    if (document.querySelectorAll(testSelector).length === 1) {
                        return testSelector;
                    }
                }

                return selector;
            }

            function getCssSelector(el) {
                if (!el) {
                    return '';
                }

                if (el.id) {
                    return '#' + escapeCssSelector(el.id);
                }

                if (el === document.body) {
                    return 'body';
                }

                let tag = el.tagName.toLowerCase();

                let validClasses = el.className.split(/\s+/).filter(cls => cls && !cls.startsWith('--'));
                let classes = validClasses.length ? '.' + validClasses.map(escapeCssSelector).join('.') : '';

                let selectorWithoutNthOfType = tag + classes;

                try {
                    let siblingsWithSameSelector = Array.from(el.parentNode.querySelectorAll(selectorWithoutNthOfType));
                    if (siblingsWithSameSelector.length === 1 && siblingsWithSameSelector[0] === el) {
                        return getCssSelector(el.parentNode) + ' > ' + selectorWithoutNthOfType;
                    }

                    let siblings = Array.from(el.parentNode.children);
                    let sameTagAndClassSiblings = siblings.filter(sib => sib.tagName === el.tagName && sib.className === el.className);
                    if (sameTagAndClassSiblings.length > 1) {
                        let index = sameTagAndClassSiblings.indexOf(el) + 1;
                        return getCssSelector(el.parentNode) + ' > ' + tag + classes + ':nth-of-type(' + index + ')';
                    } else {
                        return getCssSelector(el.parentNode) + ' > ' + tag + classes;
                    }
                } catch (e) {

                }

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

                let elem = event.target;

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
