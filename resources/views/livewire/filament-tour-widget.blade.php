<div>
    <div id="circle-cursor" class="hidden"></div>

    <div wire:ignore class="fi-modal absolute" style="display: none;" id="step-modal">
        <div class="fixed w-full flex items-center" style="z-index: 100000;">

            <div id="step-overlay"></div>

            <div class="pointer-events-none relative w-full transition my-auto p-4">
                <div id="step-window" class="fi-modal-window pointer-events-auto relative flex w-full cursor-default flex-col bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mx-auto rounded-xl max-w-sm">

                    <div class="fi-modal-header flex px-6 pt-6 gap-x-5">

                        <div>
                            <h2 id="step-modal-heading" class="absolute top-4 fi-modal-heading text-base font-semibold leading-6 text-gray-950 dark:text-white"></h2>

                            <div class="absolute end-4 top-4">
                                <button style="--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);" class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 h-9 w-9 text-gray-400 hover:text-gray-500 focus:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus:ring-primary-500 fi-modal-close-btn -m-1.5" title="Close"
                                        type="button" tabindex="-1" x-on:click="$dispatch('close-modal', { id: 'edit-user' })">
                                <span class="sr-only">
                                    Close
                                </span>

                                    <svg class="fi-icon-btn-icon h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>

                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="flex px-6 pt-6 gap-x-5 pb-2">
                        <div id="step-modal-description" class="fi-modal-description text-sm text-gray-500 dark:text-gray-400">

                        </div>
                    </div>

                    <div class="flex justify-between my-2 px-6 gap-x-5">
                        <button class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 disabled:pointer-events-none disabled:opacity-70 h-9 w-9 text-gray-400 dark:text-gray-500 dark:hover:text-gray-400 fi-modal-close-btn -m-1.5" title="Close"
                                type="button">
                            @svg('heroicon-o-arrow-left', 'h-5 w-5')
                        </button>

                        <button class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 disabled:pointer-events-none disabled:opacity-70 h-9 w-9 text-gray-400 dark:text-gray-500 dark:hover:text-gray-400 fi-modal-close-btn -m-1.5" title="Close"
                                type="button">
                            @svg('heroicon-o-arrow-right', 'h-5 w-5')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function createOverlay(element) {
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
                    modal.style.left = `${elementRect.right + 20}px`; // À droite de l'élément
                    modal.style.top = `${elementRect.top}px`;    // Aligné avec le haut de l'élément
                } else {
                    modal.style.left = "50%";
                    modal.style.top = "50%";
                    modal.style.transform = "translate(-50%, -50%)";
                }

                document.querySelector('#step-modal').style.display = 'block';
            }
        }

        document.addEventListener('livewire:initialized', function () {

            let tours = [];
            let highlights = [];

            Livewire.dispatch('filament-tour::load-elements', {request: window.location})

            Livewire.on('filament-tour::loaded-elements', function (data) {

                data.tours.forEach((tour) => {

                    tours.push(tour);

                    if (!localStorage.getItem('tours')) {
                        localStorage.setItem('tours', "[]");
                    }

                    if (tour.route === window.location.pathname) {
                        if (!data.only_visible_once) {
                            openTour(tour);
                        } else if (!localStorage.getItem('tours').includes(tour.id)) {
                            openTour(tour);
                        } else if (tour.alwaysShow || tour.ignoreRoute) {
                            openTour(tour);
                        }
                    }
                });

                data.highlights.forEach((highlight) => {

                    highlights.push(highlight);

                    if (highlight.route === window.location.pathname) {

                        waitForElement(highlight.parent, function (selector) {
                            selector.parentNode.style.position = 'relative';

                            let tempDiv = document.createElement('div');
                            tempDiv.innerHTML = highlight.button;

                            tempDiv.firstChild.classList.add(highlight.position);

                            selector.parentNode.insertBefore(tempDiv.firstChild, selector)
                        });
                    }
                });
            });

            function openTour(tour) {

                if (tour.steps.length > 0) {

                    JSON.parse(tour.steps).forEach((step) => {
                        document.body.appendChild(createOverlay(step.element));
                        positionModalBesideElement(step.element, '#step-window');

                        document.querySelector('#step-modal-heading').innerHTML = step.title;
                        document.querySelector('#step-modal-description').innerHTML = step.description;
                    })
                }
            }
        });

    </script>
</div>
