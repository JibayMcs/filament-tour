import {driver} from "driver.js";
import {initCssSelector} from './css-selector.js';

document.addEventListener('livewire:initialized', async function () {

    initCssSelector();

    let tours = [];
    let highlights = [];

    function waitForElement(selector, callback) {
        if (document.querySelector(selector)) {
            callback(document.querySelector(selector));
            return;
        }

        const observer = new MutationObserver(function (mutations) {
            if (document.querySelector(selector)) {
                callback(document.querySelector(selector));
                observer.disconnect();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    Livewire.dispatch('filament-tour::load-elements', {request: window.location})

    Livewire.on('filament-tour::loaded-elements', function (data) {

        data.tours.forEach((tour) => {

            tours.push(tour);

            if (!localStorage.getItem('tours')) {
                localStorage.setItem('tours', "[]");
            }

            if (tour.alwaysShow && tour.routesIgnored) {
                openTour(tour);
            } else if (tour.alwaysShow && !tour.routesIgnored) {
                if (tour.route === window.location.pathname) {
                    if (!data.only_visible_once ||
                        (data.only_visible_once && !localStorage.getItem('tours').includes(tour.id))) {
                        openTour(tour);
                    }
                }
            } else if (tour.routesIgnored) {
                if (!data.only_visible_once ||
                    (data.only_visible_once && !localStorage.getItem('tours').includes(tour.id))) {
                    openTour(tour);
                }
            } else if (tour.route === window.location.pathname) {
                if (!data.only_visible_once ||
                    (data.only_visible_once && !localStorage.getItem('tours').includes(tour.id))) {
                    openTour(tour);
                }
            }


        });

        data.highlights.forEach((highlight) => {

            if (highlight.route === window.location.pathname) {

                waitForElement(highlight.element, function (selector) {
                    console.log(highlight, highlight.element, selector.hasChildNodes());

                    if (selector.hasChildNodes()) {
                        console.log(selector.childNodes)
                        // highlight.element = getCssSelector();
                    }
                });

                waitForElement(highlight.parent, function (selector) {
                    selector.parentNode.style.position = 'relative';

                    let tempDiv = document.createElement('div');
                    tempDiv.innerHTML = highlight.button;

                    tempDiv.firstChild.classList.add(highlight.position);

                    selector.parentNode.insertBefore(tempDiv.firstChild, selector)
                });

                highlights.push(highlight);
            }
        });
    });

    Livewire.on('filament-tour::open-highlight', function (id) {
        let highlight = highlights.find(element => element.id === id);

        if (highlight) {
            driver({
                overlayColor: localStorage.theme === 'light' ? highlight.colors.light : highlight.colors.dark,

                onPopoverRender: (popover, {config, state}) => {
                    popover.title.innerHTML = "";
                    popover.title.innerHTML = state.activeStep.popover.title;

                    if (!state.activeStep.popover.description) {
                        popover.title.firstChild.style.justifyContent = 'center';
                    }

                    let contentClasses = "dark:text-white fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-4";

                    popover.footer.parentElement.classList.add(...contentClasses.split(" "));
                },
            }).highlight(highlight);

        } else {
            console.error(`Highlight with id '${id}' not found`);
        }
    });

    Livewire.on('filament-tour::open-tour', function (id) {
        let tour = tours.find(element => element.id === id);

        if (tour) {
            openTour(tour);
        } else {
            console.error(`Tour with id '${id}' not found`);
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
                    if (state.activeStep && (!state.activeStep.uncloseable || tour.uncloseable))
                        driverObj.destroy();

                    if (!localStorage.getItem('tours').includes(tour.id)) {
                        localStorage.setItem('tours', JSON.stringify([...JSON.parse(localStorage.getItem('tours')), tour.id]));
                    }
                }),
                onDestroyStarted: ((element, step, {config, state}) => {
                    if (state.activeStep && !state.activeStep.uncloseable && !tour.uncloseable) {
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


                    if (step.events) {

                        console.log(step.events);

                        if (step.events.notifyOnNext) {
                            new FilamentNotification()
                                .title(step.events.notifyOnNext.title)
                                .body(step.events.notifyOnNext.body)
                                .icon(step.events.notifyOnNext.icon)
                                .iconColor(step.events.notifyOnNext.iconColor)
                                .color(step.events.notifyOnNext.color)
                                .duration(step.events.notifyOnNext.duration)
                                .send();
                        }

                        if (step.events.dispatchOnNext) {
                            Livewire.dispatch(step.events.dispatchOnNext.name, step.events.dispatchOnNext.params);
                        }

                        if (step.events.clickOnNext) {
                            document.querySelector(step.events.clickOnNext).click();
                        }

                        if (step.events.redirectOnNext) {
                            window.open(step.events.redirectOnNext.url, step.events.redirectOnNext.newTab ? '_blank' : '_self');
                        }
                    }


                    driverObj.moveNext();
                }),
                onPopoverRender: (popover, {config, state}) => {

                    if (state.activeStep.uncloseable || tour.uncloseable)
                        document.querySelector(".driver-popover-close-btn").remove();

                    popover.title.innerHTML = "";
                    popover.title.innerHTML = state.activeStep.popover.title;

                    if (!state.activeStep.popover.description) {
                        popover.title.firstChild.style.justifyContent = 'center';
                    }

                    let contentClasses = "dark:text-white fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-4";

                    // popover.description.insertAdjacentHTML("beforeend", state.activeStep.popover.form);

                    popover.footer.parentElement.classList.add(...contentClasses.split(" "));

                    popover.footer.innerHTML = "";
                    popover.footer.classList.add('flex', 'mt-3');
                    popover.footer.style.justifyContent = 'space-evenly';

                    popover.footer.classList.remove("driver-popover-footer");


                    const nextButton = document.createElement("button");
                    let nextClasses = "fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-primary gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50 fi-ac-btn-action";

                    nextButton.classList.add(...nextClasses.split(" "), 'driver-popover-next-btn');
                    nextButton.innerText = driverObj.isLastStep() ? tour.doneButtonLabel : tour.nextButtonLabel;

                    nextButton.style.setProperty('--c-400', 'var(--primary-400');
                    nextButton.style.setProperty('--c-500', 'var(--primary-500');
                    nextButton.style.setProperty('--c-600', 'var(--primary-600');

                    const prevButton = document.createElement("button");
                    let prevClasses = "fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-btn-action";
                    prevButton.classList.add(...prevClasses.split(" "), 'driver-popover-prev-btn');
                    prevButton.innerText = tour.previousButtonLabel;

                    if (!driverObj.isFirstStep()) {
                        popover.footer.appendChild(prevButton);
                    }
                    popover.footer.appendChild(nextButton);
                },
                steps: JSON.parse(tour.steps),
            });

            driverObj.drive();
        }
    }
});
