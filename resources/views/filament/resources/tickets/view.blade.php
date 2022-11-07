@php($record = $this->record)
<x-filament::page>

    <div class="w-full flex md:flex-row flex-col gap-5">

        <x-filament::card class="md:w-2/3 w-full flex flex-col gap-5">
            <div class="w-full flex flex-col gap-0">
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-sm text-primary-500 font-medium">
                        <x-heroicon-o-ticket class="w-4 h-4"/>
                        {{ $record->code }}
                    </span>
                    <span class="text-sm text-gray-400 font-light">|</span>
                    <span class="flex items-center gap-1 text-sm text-gray-500">
                        {{ $record->project->name }}
                    </span>
                </div>
                <span class="text-xl text-gray-700">
                    {{ $record->name }}
                </span>
            </div>
            <div class="w-full flex items-center gap-2">
                <div class="px-2 py-1 rounded flex items-center justify-center text-center text-xs text-white"
                     style="background-color: {{ $record->status->color }};">
                    {{ $record->status->name }}
                </div>
                <div class="px-2 py-1 rounded flex items-center justify-center text-center text-xs text-white"
                     style="background-color: {{ $record->priority->color }};">
                    {{ $record->priority->name }}
                </div>
                <div class="px-2 py-1 rounded flex items-center justify-center text-center text-xs text-white"
                     style="background-color: {{ $record->type->color }};">
                    <x-icon class="h-3 text-white" name="{{ $record->type->icon }}"/>
                    <span class="ml-2">
                        {{ $record->type->name }}
                    </span>
                </div>
            </div>
            <div class="w-full flex flex-col gap-0 pt-5">
                <span class="text-gray-500 text-sm font-medium">
                    {{ __('Content') }}
                </span>
                <div class="w-full prose">
                    {!! $record->content !!}
                </div>
            </div>
        </x-filament::card>

        <x-filament::card class="md:w-1/3 w-full flex flex-col">
            <div class="w-full flex flex-col gap-1">
                <span class="text-gray-500 text-sm font-medium">
                    {{ __('Owner') }}
                </span>
                <div class="w-full flex items-center gap-1 text-gray-500">
                    <img src="{{ $record->owner->avatar_url }}"
                         alt="{{ $record->owner->name }}"
                         class="w-6 h-6 rounded-full bg-gray-200 bg-cover bg-center"/>
                    {{ $record->owner->name }}
                </div>
            </div>

            <div class="w-full flex flex-col gap-1 pt-3">
                <span class="text-gray-500 text-sm font-medium">
                    {{ __('Responsible') }}
                </span>
                <div class="w-full flex items-center gap-1 text-gray-500">
                    @if($record->responsible)
                        <img src="{{ $record->responsible->avatar_url }}"
                             alt="{{ $record->responsible->name }}"
                             class="w-6 h-6 rounded-full bg-gray-200 bg-cover bg-center"/>
                    @endif
                    {{ $record->responsible?->name ?? '-' }}
                </div>
            </div>

            <div class="w-full flex flex-col gap-1 pt-3">
                <span class="text-gray-500 text-sm font-medium">
                    {{ __('Creation date') }}
                </span>
                <div class="w-full text-gray-500">
                    {{ $record->created_at->format(__('Y-m-d g:i A')) }}
                    <span class="text-xs text-gray-400">
                        ({{ $record->created_at->diffForHumans() }})
                    </span>
                </div>
            </div>

            <div class="w-full flex flex-col gap-1 pt-3">
                <span class="text-gray-500 text-sm font-medium">
                    {{ __('Last update') }}
                </span>
                <div class="w-full text-gray-500">
                    {{ $record->updated_at->format(__('Y-m-d g:i A')) }}
                    <span class="text-xs text-gray-400">
                        ({{ $record->updated_at->diffForHumans() }})
                    </span>
                </div>
            </div>
        </x-filament::card>

    </div>

    <div class="w-full flex md:flex-row flex-col gap-5">

        <x-filament::card class="md:w-2/3 w-full flex flex-col">
            <div class="w-full flex items-center gap-2">
                <button wire:click="selectTab('comments')"
                        class="text-xl p-3 border-b-2 border-transparent hover:border-primary-500 flex items-center
                        gap-1 @if($tab === 'comments') border-primary-500 text-primary-500 @else text-gray-700 @endif">
                    {{ __('Comments') }}
                </button>
                <button wire:click="selectTab('activities')"
                        class="text-xl p-3 border-b-2 border-transparent hover:border-primary-500
                        @if($tab === 'activities') border-primary-500 text-primary-500 @else text-gray-700 @endif">
                    {{ __('Activities') }}
                </button>
            </div>
            @if($tab === 'comments')
                <form wire:submit.prevent="submitComment" class="pb-5">
                    {{ $this->form }}
                    <button type="submit"
                            class="px-3 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded mt-3">
                        {{ __('Add comment') }}
                    </button>
                </form>
                @foreach($record->comments->sortByDesc('created_at') as $comment)
                    <div class="w-full flex flex-col gap-2 @if(!$loop->last) pb-5 mb-5 border-b border-gray-200 @endif">
                        <span class="flex items-center gap-1 text-gray-500 text-sm">
                            <span class="font-medium flex items-center gap-1">
                                <img src="{{ $comment->user->avatar_url }}"
                                     alt="{{ $comment->user->name }}"
                                     class="w-6 h-6 rounded-full bg-gray-200 bg-cover bg-center"/>
                                {{ $comment->user->name }}
                            </span>
                            <span class="text-gray-400 px-2">|</span>
                            {{ $comment->created_at->format('Y-m-d g:i A') }}
                            ({{ $comment->created_at->diffForHumans() }})
                        </span>
                        <div class="w-full prose">
                            {!! $comment->content !!}
                        </div>
                    </div>
                @endforeach
            @endif
            @if($tab === 'activities')
                <div class="w-full flex flex-col pt-5">
                    @if($record->activities->count())
                        @foreach($record->activities->sortByDesc('created_at') as $activity)
                            <div class="w-full flex flex-col gap-2
                                 @if(!$loop->last) pb-5 mb-5 border-b border-gray-200 @endif">
                                <span class="flex items-center gap-1 text-gray-500 text-sm">
                                    <span class="font-medium flex items-center gap-1">
                                        <img src="{{ $activity->user->avatar_url }}"
                                             alt="{{ $activity->user->name }}"
                                             class="w-6 h-6 rounded-full bg-gray-200 bg-cover bg-center"/>
                                        {{ $activity->user->name }}
                                    </span>
                                    <span class="text-gray-400 px-2">|</span>
                                    {{ $activity->created_at->format('Y-m-d g:i A') }}
                                    ({{ $activity->created_at->diffForHumans() }})
                                </span>
                                <div class="w-full flex items-center gap-10">
                                    <span class="text-gray-400">{{ $activity->oldStatus->name }}</span>
                                        <x-heroicon-o-arrow-right class="w-6 h-6" />
                                        <span style="color: {{ $activity->newStatus->color }}">
                                        {{ $activity->newStatus->name }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <span class="text-gray-400 text-sm font-medium">
                        {{ __('No activities yet!') }}
                    </span>
                    @endif
                </div>
            @endif
        </x-filament::card>

    </div>

</x-filament::page>

@push('scripts')
    <script>
        window.addEventListener('shareTicket', (e) => {
            const text = e.detail.url;
            const textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
            } catch (err) {
                console.error('Unable to copy to clipboard', err);
            }
            document.body.removeChild(textArea);
            new Notification()
                .success()
                .title('{{ __('Url copied to clipboard') }}')
                .duration(6000)
                .send()
        });
    </script>
@endpush
