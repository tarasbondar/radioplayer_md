<div class="author__wrapper">
    <form action="/send-application" method='POST' id="apply-form" {{--data-validate="apply-form"--}} enctype='multipart/form-data' data-episode-form>
        @csrf

        <div class="input form-floating">
            <input type="text" class="form-control" placeholder="{{ __('client.podcastTitle') }}" id="title" name="title" value="{{ old('title') }}">
            <label for="title">{{ __('client.podcastTitle') }}</label>
            <div class="messages">
                {{ $errors->has('title') ? $errors->first('title') : '' }}
            </div>
        </div>

        <div class="input form-floating">
            <textarea class="form-control" placeholder="{{ __('client.podcastDescription') }}" id="description" name="description"> {{ old('description') }}</textarea>
            <label for="description">{{ __('client.podcastDescription') }}</label>
            <div class="messages">
                {{ $errors->has('description') ? $errors->first('description') : '' }}
            </div>
        </div>

        <div class="input">
            <div class="form-floating">
                <input type="hidden" id="categories-ids" name="categories-ids">
                <input class="form-control" type="text" placeholder="{{ __('client.category') }}" id="categories" name="categories" readonly>
                <label for="categories">{{ __('client.category') }}</label>
                <button class="btn-modal-toggle-wrapper" type="button" aria-label="{{ __('client.categories') }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <span class="btn btn-modal-toggle">
                        <svg class="icon">
                            <use href="/img/sprite.svg#chevron-right"></use>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="messages">
                {{ $errors->has('categories-ids') ? $errors->first('categories-ids') : '' }}
            </div>
        </div>

        <div class="input form-floating">
            <input type="text" class="form-control" placeholder="{{ __('client.tagsComma') }}" id="tags" name="tags" value="{{ old('tags') }}">
            <label for="tags">{{ __('client.tagsComma') }}</label>
            <div class="messages">
                {{ $errors->has('tags') ? $errors->first('tags') : '' }}
            </div>
        </div>

        <div class="input file">
            <label for="image" class="control-panel">
                <input id="image" class="form__file" type="file" data-file_input="file-image" size="1048576" name="image" accept="image/png, image/jpeg, image/*">
                <span class="control-panel-wrap">
                    <span class="control-panel-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                            <path d="M19.5 3H5.5C4.39543 3 3.5 3.89543 3.5 5V19C3.5 20.1046 4.39543 21 5.5 21H19.5C20.6046 21 21.5 20.1046 21.5 19V5C21.5 3.89543 20.6046 3 19.5 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 10C9.82843 10 10.5 9.32843 10.5 8.5C10.5 7.67157 9.82843 7 9 7C8.17157 7 7.5 7.67157 7.5 8.5C7.5 9.32843 8.17157 10 9 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21.5 15L16.5 10L5.5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="control-panel-info">
                        <span class="h4 authorization-list-card__title">{{ __('client.podcastImage') }}</span>
                        <span class="authorization-list-card__desc">{{ __('client.podcastImageRequirements') }}</span>
                        <img class="authorization-list-card__preview" src="" alt="preview">
                        <i class="control-panel-delete icon-close">
                            <svg class="icon">
                                <use href="/img/sprite.svg#x"></use>
                            </svg>
                        </i>
                    </span>
                </span>
            </label>
            {{ $errors->has('image') ? $errors->first('image') : '' }}
        </div>

        <div class="input file">
            <label for="file-audio" class="control-panel">
                <input id="file-audio" class="form__file" type="file" data-file_input="audio" name="audio" size="52428800" accept=".MP3, .WAV">
                <span class="control-panel-wrap">
                    <span class="control-panel-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                            <path d="M9.5 18V5L21.5 3V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.5 21C8.15685 21 9.5 19.6569 9.5 18C9.5 16.3431 8.15685 15 6.5 15C4.84315 15 3.5 16.3431 3.5 18C3.5 19.6569 4.84315 21 6.5 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.5 19C20.1569 19 21.5 17.6569 21.5 16C21.5 14.3431 20.1569 13 18.5 13C16.8431 13 15.5 14.3431 15.5 16C15.5 17.6569 16.8431 19 18.5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="control-panel-info">
                        <span class="h4 authorization-list-card__title">{{ __('client.episodeExample') }}</span>
                        <span class="authorization-list-card__desc">{{ __('client.fileRequirements') }}</span>
                        <i class="control-panel-delete icon-close">
                            <svg class="icon">
                                <use href="/img/sprite.svg#x"></use>
                            </svg>
                        </i>
                    </span>
                </span>
            </label>

            <ul class="podcast__files-list" data-file-list>
                <li class="podcast__files-list-item hidden" data-file-item>
                    <div class="file">
                        <div class="control-panel control-panel_loaded">
                            <div class="control-panel-wrap">
                                <div class="control-panel-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                        <path d="M9.5 18V5L21.5 3V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.5 21C8.15685 21 9.5 19.6569 9.5 18C9.5 16.3431 8.15685 15 6.5 15C4.84315 15 3.5 16.3431 3.5 18C3.5 19.6569 4.84315 21 6.5 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M18.5 19C20.1569 19 21.5 17.6569 21.5 16C21.5 14.3431 20.1569 13 18.5 13C16.8431 13 15.5 14.3431 15.5 16C15.5 17.6569 16.8431 19 18.5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="control-panel-info">
                                    <span class="h4 authorization-list-card__title" data-item-filename></span>
                                    <span class="authorization-list-card__desc"><span data-item-uploaded></span> / <span data-item-size></span></span>
                                    <div class="progress__bar">
                                        <div class="progress__line" style="width:100%"></div>
                                    </div>
                                    <span class="control-panel-percent"><span>0</span>%</span>
                                    <i class="control-panel-delete icon-close" data-file-remove>
                                        <svg class="icon">
                                            <use href="/img/sprite.svg#x"></use>
                                        </svg>
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="messages"> {{ $errors->has('audio') ? $errors->first('audio') : '' }} </div>
        </div>

        <div class="input input__inner">
            <input class="input__checkbox" type="checkbox" id="privacy" name="privacy" data-agree>
            <label class="input__label" for="privacy">
                Я принимаю условия <a href="/privacy-policy" class="link" target="_blank">пользовательского соглашения</a>
            </label>
            <svg class="icon">
                <use href="/img/sprite.svg#check"></use>
            </svg>
            <div class="messages">
                {{ $errors->has('privacy') ? $errors->first('privacy') : '' }}
            </div>
        </div>
        <button class="btn btn_default btn_primary" type="submit" id="form-submit">{{ __('app.sendApplication') }}</button>

    </form>
</div>



<script>

    $(document).on('click', '.close-categories', function() {
        let selected_names = [];
        let selected_ids = [];
        $('.categories-list input:checked').each(function() {
            selected_names.push($(this).attr('name'));
            selected_ids.push($(this).val());
        });

        $('#categories').val(selected_names.join(', '));
        $('#categories-ids').val(selected_ids.join(','));
    });

    (function(){
        let $form = $('[data-episode-form]');
        $(document).on('change', '[data-episode-form] input[name="audio"]', function(){
            let selectedFile = this.files[0];
            let fileName = selectedFile.name;
            let fileSizeBytes = selectedFile.size;
            let sizeFormatted = core.formatBytes(fileSizeBytes, 2);
            $form.find('[data-item-filename]').html(fileName);
            $form.find('[data-item-uploaded]').html(sizeFormatted);
            $form.find('[data-item-size]').html(sizeFormatted);
            $form.find('[data-file-item]').removeClass('hidden');
        });
        $(document).on('click', '[data-file-remove]', function(){
            $('[data-file-list] [data-file-item]').addClass('hidden');
            $form.find('input[name="audio"]').val('');
        });

    })(jQuery)
</script>
