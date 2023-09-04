import {initCssSelector} from "./css-selector.js";

document.addEventListener('livewire:initialized', async function () {

    initCssSelector();

    let currentOverlay;

    function createOverlay(element, stepId) {
        const windowX = window.innerWidth;
        const windowY = window.innerHeight;

        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");

        svg.setAttribute("viewBox", `0 0 ${windowX} ${windowY}`);
        svg.setAttribute("xmlSpace", "preserve");
        svg.setAttribute("xmlnsXlink", "http://www.w3.org/1999/xlink");
        svg.setAttribute("version", "1.1");
        svg.setAttribute("preserveAspectRatio", "xMinYMin slice");

        svg.style.fillRule = "evenodd";
        svg.style.clipRule = "evenodd";
        svg.style.strokeLinejoin = "round";
        svg.style.strokeMiterlimit = "2";
        svg.style.zIndex = "10000";
        svg.style.position = "fixed";
        svg.style.top = "0";
        svg.style.left = "0";
        svg.style.width = "100%";
        svg.style.height = "100%";

        const stagePath = document.createElementNS("http://www.w3.org/2000/svg", "path");

        stagePath.setAttribute("d", generateStageSvgPathString(element));

        stagePath.style.fill = "#fff";
        stagePath.style.opacity = "0.5";
        stagePath.style.pointerEvents = "auto";
        stagePath.style.cursor = "auto";

        svg.appendChild(stagePath);

        svg.id = `step-${stepId}-overlay`;

        return svg;
    }

    function generateStageSvgPathString(element) {

        element = document.querySelector(element);

        const windowX = window.innerWidth;
        const windowY = window.innerHeight;

        const stagePadding = 10;
        const stageRadius = 10;

        const stageWidth = element ? element.clientWidth + stagePadding * 2 : 0;
        const stageHeight = element ? element.clientHeight + stagePadding * 2 : 0;

        // prevent glitches when stage is too small for radius
        const limitedRadius = Math.min(stageRadius, stageWidth / 2, stageHeight / 2);

        // no value below 0 allowed + round down
        const normalizedRadius = Math.floor(Math.max(limitedRadius, 0));

        const highlightBoxX = element ? element.offsetLeft - stagePadding + normalizedRadius : 0;
        const highlightBoxY = element ? element.offsetTop - stagePadding : 0;
        const highlightBoxWidth = stageWidth - normalizedRadius * 2;
        const highlightBoxHeight = stageHeight - normalizedRadius * 2;

        return `M${windowX},0L0,0L0,${windowY}L${windowX},${windowY}L${windowX},0Z
    M${highlightBoxX},${highlightBoxY} h${highlightBoxWidth} a${normalizedRadius},${normalizedRadius} 0 0 1 ${normalizedRadius},${normalizedRadius} v${highlightBoxHeight} a${normalizedRadius},${normalizedRadius} 0 0 1 -${normalizedRadius},${normalizedRadius} h-${highlightBoxWidth} a${normalizedRadius},${normalizedRadius} 0 0 1 -${normalizedRadius},-${normalizedRadius} v-${highlightBoxHeight} a${normalizedRadius},${normalizedRadius} 0 0 1 ${normalizedRadius},-${normalizedRadius} z`;
    }

    function positionModalBesideElement(elementSelector, modalSelector) {
        const element = document.querySelector(elementSelector);
        const modal = document.querySelector(modalSelector);

        if (modal) {
            modal.style.position = "fixed";

            if (element) {
                const elementRect = element.getBoundingClientRect();
                modal.style.left = `${elementRect.right + element.width}px`; // À droite de l'élément
                modal.style.top = `${elementRect.top}px`;    // Aligné avec le haut de l'élément
            } else {
                modal.style.left = "50%";
                modal.style.top = "50%";
                modal.style.transform = "translate(-50%, -50%)";
            }

            document.querySelector('#step-modal').style.display = 'block';
        }
    }


    Livewire.dispatch('filament-tour::load-elements', {request: window.location})

    Livewire.on('filament-tour::close', () => {
        if (currentOverlay) {
            document.body.removeChild(currentOverlay);
            document.querySelector('#step-modal').style.display = 'none';
        }
    });

    Livewire.on('filament-tour::updateStep', ({step}) => {

        if (currentOverlay) {
            document.body.removeChild(currentOverlay);
        }

        currentOverlay = createOverlay(step.element);
        document.body.appendChild(currentOverlay);

        positionModalBesideElement(step.element, '#step-window');

        document.querySelector('#step-modal-heading').innerHTML = step.title;
        document.querySelector('#step-modal-description').innerHTML = step.description;
    });

});
