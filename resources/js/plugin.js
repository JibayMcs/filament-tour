import {driver} from "driver.js";

document.addEventListener('livewire:load', function () {

    let tours = [];
    let highlights = [];

    Livewire.emit('driverjs::load-elements', window.location);

    Livewire.on('driverjs::loaded-elements', function (data) {

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
                } else if (tour.alwaysShow) {
                    openTour(tour);
                }
            }
        });

        data.highlights.forEach((highlight) => {
            highlights.push(highlight);

            if (highlight.route === window.location.pathname) {

                if (document.querySelector(highlight.parent)) {
                    parent = document.querySelector(highlight.parent);

                    parent.parentNode.style.position = 'relative';

                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = highlight.button;

                    tempDiv.firstChild.classList.add(highlight.position);

                    parent.parentNode.insertBefore(tempDiv.firstChild, parent)
                    // parent.innerHTML += highlight.button;
                }
            }
        });
    });

    Livewire.on('driverjs::open-highlight', function (highlight) {
        let highlightElement = highlights.find(element => element.id === highlight.highlight);

        if (highlightElement) {

            driver({
                overlayColor: localStorage.theme === 'light' ? highlightElement.colors.light : highlightElement.colors.dark,

                onPopoverRender: (popover, {config, state}) => {
                    popover.title.innerHTML = "";
                    popover.title.innerHTML = state.activeStep.popover.title;

                    if (!state.activeStep.popover.description) {
                        popover.title.firstChild.style.justifyContent = 'center';
                    }

                    let contentClasses = "dark:text-white fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-4";

                    popover.footer.parentElement.classList.add(...contentClasses.split(" "));
                },
            }).highlight(highlightElement);
        }
    });

    Livewire.on('driverjs::open-tour', function (tour) {
        let tourElement = tours.find(element => element.id === tour);

        if (tourElement) {
            openTour(tourElement);
        } else {
            console.error(`Tour with id '${tour}' not found`);
        }
    });

    function openTour(tour) {

        if (tour.steps.length > 0) {

            const driverObj = driver({
                allowClose: true,
                disableActiveInteraction: true,
                overlayColor: localStorage.theme === 'light' ? tour.colors.light : tour.colors.dark,
                onDeselected: ((element, step, {config, state}) => {
                }),
                onCloseClick: ((element, step, {config, state}) => {
                    if (state.activeStep && !state.activeStep.uncloseable)
                        driverObj.destroy();

                    if (!localStorage.getItem('tours').includes(tour.id)) {
                        localStorage.setItem('tours', JSON.stringify([...JSON.parse(localStorage.getItem('tours')), tour.id]));
                    }
                }),
                onDestroyStarted: ((element, step, {config, state}) => {
                    if (state.activeStep && !state.activeStep.uncloseable) {
                        driverObj.destroy();
                    }
                }),
                onDestroyed: ((element, step, {config, state}) => {

                }),
                onNextClick: ((element, step, {config, state}) => {

                    if (driverObj.isLastStep()) {

                        if (!localStorage.getItem('tours').includes(tour.id)) {
                            localStorage.setItem('tours', JSON.stringify([...JSON.parse(localStorage.getItem('tours')), tour.id]));
                        }

                        driverObj.destroy();
                    }

                    if (step.onNextNotify) {
                        new Notification()
                            .title(step.onNextNotify.title)
                            .body(step.onNextNotify.body)
                            .icon(step.onNextNotify.icon)
                            .iconColor(step.onNextNotify.iconColor)
                            .color(step.onNextNotify.color)
                            .duration(step.onNextNotify.duration)
                            .send();
                    }

                    if (step.onNextDispatch) {
                        Livewire.emit(step.onNextDispatch.name, JSON.parse(step.onNextDispatch.args))
                    }

                    if (step.onNextClickSelector) {
                        document.querySelector(step.onNextClickSelector).click();
                    }

                    if (step.onNextRedirect) {
                        window.open(step.onNextRedirect.url, step.onNextRedirect.newTab ? '_blank' : '_self');
                    }

                    driverObj.moveNext();
                }),
                onPopoverRender: (popover, {config, state}) => {
                    if (state.activeStep.uncloseable)
                        document.querySelector(".driver-popover-close-btn").remove();

                    popover.title.innerHTML = "";
                    popover.title.innerHTML = state.activeStep.popover.title;

                    if (!state.activeStep.popover.description) {
                        popover.title.firstChild.style.justifyContent = 'center';
                    }

                    let contentClasses = "dark:text-white fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-4";

                    popover.footer.parentElement.classList.add(...contentClasses.split(" "));

                    popover.footer.innerHTML = "";
                    popover.footer.classList.add('flex', 'mt-3');
                    popover.footer.style.justifyContent = 'space-evenly';

                    popover.footer.classList.remove("driver-popover-footer");


                    const nextButton = document.createElement("button");
                    let nextClasses = "filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action";

                    nextButton.classList.add(...nextClasses.split(" "), 'driver-popover-next-btn');
                    nextButton.innerText = driverObj.isLastStep() ? tour.doneButtonLabel : tour.nextButtonLabel;

                    const prevButton = document.createElement("button");
                    let prevClasses = "filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-600 dark:hover:border-gray-500 dark:text-gray-200 dark:focus:text-primary-400 dark:focus:border-primary-400 dark:focus:bg-gray-800 filament-page-button-action";
                    prevButton.classList.add(...prevClasses.split(" "), 'driver-popover-prev-btn');
                    prevButton.innerText = tour.previousButtonLabel;

                    if (!driverObj.isFirstStep()) {
                        popover.footer.appendChild(prevButton);
                    }
                    popover.footer.appendChild(nextButton);
                },
                steps:
                    JSON.parse(tour.steps),
            });

            driverObj.drive();
        }
    }
});

