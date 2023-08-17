import { driver } from "driver.js";
// import "driver.js/dist/driver.css";

document.addEventListener('livewire:initialized', async function () {
        console.log('livewire:initialized');


        Livewire.dispatch('driverjs::load-tutorials', {request: window.location});

        Livewire.on('driverjs::selector', function (selector) {
            let element = Livewire.find(selector[0].selector);

            let elements = [];

            function onPress(event) {
                if (event.key === 'Escape' || event.keyCode === 27) {
                    elements.forEach((element) => element.classList.remove('highlighted'));
                    elements = [];

                    document.removeEventListener('mouseenter', handleMouseEnter, true);
                    document.removeEventListener('mouseleave', handleMouseLeave, true);
                    document.removeEventListener('click', handleClick, true);
                    document.removeEventListener('keydown', onPress, true);
                }
            }

            function handleMouseEnter(event) {
                event.target.classList.add('highlighted');
                elements.push(event.target);
            }

            function handleMouseLeave(event) {
                event.target.classList.remove('highlighted');
            }

            function handleClick(event) {

                event.preventDefault();
                event.stopPropagation();

                const path = getElementSelector(event.target);
                navigator.clipboard.writeText(path);

                element.set(selector[0].component_id, path)

                elements.forEach((element) => element.classList.remove('highlighted'));
                elements = [];

                // Supprimer les écouteurs d'événements
                document.removeEventListener('mouseenter', handleMouseEnter, true);
                document.removeEventListener('mouseleave', handleMouseLeave, true);
                document.removeEventListener('click', handleClick, true);
                document.removeEventListener('keydown', onPress, true);

            }

            document.addEventListener('mouseenter', handleMouseEnter, true);
            document.addEventListener('mouseleave', handleMouseLeave, true);
            document.addEventListener('click', handleClick, true);
            document.addEventListener('keydown', onPress, true);

        });

        Livewire.on('driverjs::loaded-tutorials', function (data) {
            data[0].tutorials.forEach((tutorial) => {
                if (!localStorage.getItem('tutorials')) {
                    localStorage.setItem('tutorials', "[]");
                }
                if (tutorial.open && !localStorage.getItem('tutorials').includes(tutorial.id)) {
                    openTutorial(tutorial);
                }
            });
        });

        Livewire.on('driverjs::open-tutorial', function (tutorial) {
            openTutorial(tutorial[0]);
        });

        function getElementSelector(element) {
            const parts = [];
            while (element && element.nodeType === Node.ELEMENT_NODE) {
                let selector = element.nodeName.toLowerCase();
                if (element.id) {
                    selector += '#' + element.id;
                    parts.unshift(selector);
                    break;  // ID est unique, donc on peut arrêter ici
                } else {
                    let s = selector;
                    const parent = element.parentNode;
                    if (parent) {
                        const siblings = Array.from(parent.children);
                        const idx = siblings.indexOf(element);
                        if (idx !== -1) {
                            s += `:nth-child(${idx + 1})`;
                        }
                    }
                    parts.unshift(s);
                    element = parent;
                }
            }
            return parts.join(' > ');
        }

        function openTutorial(tutorial) {
            if (tutorial.steps.length > 0) {

                const driverObj = driver({
                    allowClose: true,
                    disableActiveInteraction: true,
                    overlayColor: localStorage.theme === 'light' ? tutorial.colors.light : tutorial.colors.dark,
                    onDeselected: ((element, step, {config, state}) => {
                    }),
                    onCloseClick: ((element, step, {config, state}) => {
                        if (state.activeStep && !state.activeStep.popover.unclosable)
                            driverObj.destroy();
                    }),
                    onDestroyStarted: ((element, step, {config, state}) => {
                        if (state.activeStep && !state.activeStep.popover.unclosable) {
                            driverObj.destroy();
                        }
                    }),
                    onDestroyed: ((element, step, {config, state}) => {
                        if (!localStorage.getItem('tutorials').includes(tutorial.id)) {
                            localStorage.setItem('tutorials', JSON.stringify([...JSON.parse(localStorage.getItem('tutorials')), tutorial.id]));
                        }
                    }),
                    onNextClick: ((element, step, {config, state}) => {

                        if (driverObj.isLastStep()) {
                            driverObj.destroy();
                        }

                        if (step.popover.onNext) {
                            // eval(step.popover.onNext);
                        }

                        if (step.redirect) {
                            window.open(step.redirect.url, step.redirect.newTab ? '_blank' : '_self');
                        }

                        driverObj.moveNext();
                    }),
                    onPopoverRender:
                        (popover, {config, state}) => {
                            if (state.activeStep.popover.unclosable)
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
                            let nextClasses = "fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-primary gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50 fi-ac-btn-action";

                            nextButton.classList.add(...nextClasses.split(" "), 'driver-popover-next-btn');
                            nextButton.innerText = driverObj.isLastStep() ? "Terminer" : "Suivant";

                            nextButton.style.setProperty('--c-400', 'var(--primary-400');
                            nextButton.style.setProperty('--c-500', 'var(--primary-500');
                            nextButton.style.setProperty('--c-600', 'var(--primary-600');

                            const prevButton = document.createElement("button");
                            let prevClasses = "fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-btn-action";
                            prevButton.classList.add(...prevClasses.split(" "), 'driver-popover-prev-btn');
                            prevButton.innerText = "Précédent";

                            const exitButton = document.createElement("button");
                            let exitClasses = "fi-btn fi-btn-size-sm relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-danger gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50 fi-ac-btn-action";
                            exitButton.classList.add(...exitClasses.split(" "));
                            exitButton.innerText = "Quitter";

                            exitButton.style.setProperty('--c-400', 'var(--danger-400)');
                            exitButton.style.setProperty('--c-500', 'var(--danger-500');
                            exitButton.style.setProperty('--c-600', 'var(--danger-600');

                            exitButton.addEventListener('click', () => {
                                driverObj.destroy();
                            });

                            if (!driverObj.isLastStep() && !state.activeStep.popover.unclosable) {
                                popover.footer.appendChild(exitButton);
                            }

                            if (!driverObj.isFirstStep()) {
                                popover.footer.appendChild(prevButton);
                            }
                            popover.footer.appendChild(nextButton);
                        },
                    steps:
                        JSON.parse(tutorial.steps),
                });

                driverObj.drive();
            }
        }
    }
)
;

