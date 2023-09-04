<div>
    <div id="circle-cursor" class="hidden"></div>

    <div wire:ignore
         class="fi-modal absolute" style="display: none;" id="step-modal">
        <div class="fixed w-full flex items-center" style="z-index: 100000;">

            <div id="step-overlay"></div>

            <div class="pointer-events-none relative w-full transition my-auto p-4">
                <div id="step-window" class="fi-modal-window pointer-events-auto relative flex w-full cursor-default flex-col bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mx-auto rounded-xl max-w-sm">

                    <div class="fi-modal-header flex px-6 pt-6 gap-x-5">

                        <div>
                            <h2 id="step-modal-heading" class="absolute top-4 fi-modal-heading text-base font-semibold leading-6 text-gray-950 dark:text-white"></h2>

                            <div class="absolute end-4 top-4">
                                <button
                                    class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 disabled:pointer-events-none disabled:opacity-70 h-9 w-9 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 fi-modal-close-btn -m-1.5" title="Close"
                                    wire:click="close">

                                    @svg('heroicon-o-x-mark', 'h-5 w-5')

                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="flex flex-col px-6 pt-6 gap-x-5 pb-2">
                        <div id="step-modal-description" class="fi-modal-description text-sm text-gray-500 dark:text-gray-400">
                        </div>

                        <div class="mt-2">
                            {{--                            {{ $this->form }}--}}
                        </div>

                    </div>

                    <div class="flex justify-between my-2 px-6 gap-x-5">
                        <button class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 disabled:pointer-events-none disabled:opacity-70 h-9 w-9 text-gray-400 dark:text-gray-500 dark:hover:text-gray-400 fi-modal-close-btn -m-1.5" title="Close"
                                wire:click="previousStep"
                                type="button">
                            @svg('heroicon-o-arrow-left', 'h-5 w-5')
                        </button>

                        <button class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 disabled:pointer-events-none disabled:opacity-70 h-9 w-9 text-gray-400 dark:text-gray-500 dark:hover:text-gray-400 fi-modal-close-btn -m-1.5" title="Close"
                                wire:click="nextStep"
                                type="button">
                            @svg('heroicon-o-arrow-right', 'h-5 w-5')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
