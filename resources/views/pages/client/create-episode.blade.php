<?php
use Illuminate\Support\Facades\File;
use App\Models\PodcastEpisode;
use App\Helpers\SiteHelper;
?>

@php
    $path = public_path(PodcastEpisode::UPLOADS_AUDIO.'/'.@$episode['source']);
    $sizeInBytes = File::size($path);
    $sizeInKilobytes = SiteHelper::formatBytes($sizeInBytes, 2);
@endphp

@extends('layouts/client')

@section('content')

        <main class="main">
            <section class="author">
                <div class="container">
                    <div class="author__wrapper">
                        <form action="/save-episode" method="POST" id="apply-form" enctype="multipart/form-data" data-episode-form>

                            @csrf
                            <input type="hidden" name="file_remove" value="0" />

                            <div class="form-group row" hidden>
                                <input id="id" type="text" class="form-control" name="id" value="{{ @$episode['id'] }}" readonly>
                            </div>

                            <div class="form-group row" hidden>
                                <input id="podcast_id" type="text" class="form-control" name="podcast-id" value="{{ @$podcast_id }}" readonly>
                            </div>

                            <div class="input form-floating">
                                <input type="text" class="form-control" placeholder="{{ __('client.episodeTitle') }}" id="name" name="name" value="{{ @$episode['name'] }}" required>
                                <label for="name">{{ __('client.episodeTitle') }}</label>
                                <div class="messages"></div>
                            </div>

                            <div class="input form-floating">
                                <textarea class="form-control" placeholder="{{ __('client.episodeDescription') }}" id="description" name="description">{{ @$episode['description'] }}</textarea>
                                <label for="description">{{ __('client.episodeDescription') }}</label>
                                <div class="messages"></div>
                            </div>

                            <div class="input form-floating">
                                <input type="text" class="form-control" placeholder="{{ __('client.tagsComma') }}" id="tags" name="tags" value="{{ @$episode['tags'] }}">
                                <label for="tags">{{ __('client.tagsComma') }}</label>
                                <div class="messages"></div>
                            </div>


                            <div class="input file">
                                <label for="file-audio" class="control-panel">
                                    <input id="file-audio" class="form__file" name="source" type="file" size="52428800" accept=".MP3, .WAV">
                                    <span class="control-panel-wrap">
                                        <span class="control-panel-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                <path d="M9.5 18V5L21.5 3V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6.5 21C8.15685 21 9.5 19.6569 9.5 18C9.5 16.3431 8.15685 15 6.5 15C4.84315 15 3.5 16.3431 3.5 18C3.5 19.6569 4.84315 21 6.5 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.5 19C20.1569 19 21.5 17.6569 21.5 16C21.5 14.3431 20.1569 13 18.5 13C16.8431 13 15.5 14.3431 15.5 16C15.5 17.6569 16.8431 19 18.5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <span class="control-panel-info">
                                            <span class="h4 authorization-list-card__title">{{ __('client.podcastExample') }}</span>
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
                                    <li class="podcast__files-list-item {{ (@$episode['source']) ? '' : 'hidden' }}" data-file-item>
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
                                                        <span class="h4 authorization-list-card__title" data-item-filename>{{ @$episode['filename']  }}</span>
                                                        <span class="authorization-list-card__desc"><span data-item-uploaded>{{ $sizeInKilobytes }}</span> / <span data-item-size>{{ $sizeInKilobytes  }}</span></span>
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
                            </div>
                            {{--<div class="input file">
                                <label for="file-audio" class="control-panel">
                                    <input id="file-audio" class="form__file" type="file" data-file_input="file-audio" size="52428800" accept=".MP3, .WAV">
                                    <span class="control-panel-wrap">
								            <span class="control-panel-icon">
									            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
													<path d="M9.5 18V5L21.5 3V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
													<path d="M6.5 21C8.15685 21 9.5 19.6569 9.5 18C9.5 16.3431 8.15685 15 6.5 15C4.84315 15 3.5 16.3431 3.5 18C3.5 19.6569 4.84315 21 6.5 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
													<path d="M18.5 19C20.1569 19 21.5 17.6569 21.5 16C21.5 14.3431 20.1569 13 18.5 13C16.8431 13 15.5 14.3431 15.5 16C15.5 17.6569 16.8431 19 18.5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
								            </span>
								            <span class="control-panel-info">
									            <span class="h4 authorization-list-card__title">{{ __('client.podcastExample') }}</span>
									            <span class="authorization-list-card__desc">{{ __('client.fileRequirements') }}</span>
									            <i class="control-panel-delete icon-close">
										            <svg class="icon">
							                            <use href="/img/sprite.svg#x"></use>
							                        </svg>
									            </i>
								            </span>
							            </span>
                                </label>

                                <ul class="podcast__files-list">
                                    <li class="podcast__files-list-item">
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
                                                        <span class="h4 authorization-list-card__title">example.wav</span>
                                                        <span class="authorization-list-card__desc"><span>125 kb</span> / 1 MB</span>
                                                        <div class="progress__bar">
                                                            <div class="progress__line" style="width:100%"></div>
                                                        </div>
                                                        <span class="control-panel-percent"><span>0</span>%</span>
                                                        <i class="control-panel-delete icon-close">
                                                            <svg class="icon">
                                                                <use href="/img/sprite.svg#x"></use>
                                                            </svg>
                                                        </i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="podcast__files-list-item">
                                        <div class="file">
                                            <div class="control-panel control-panel_load">
                                                <div class="control-panel-wrap">
                                                    <div class="control-panel-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                            <path d="M9.5 18V5L21.5 3V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M6.5 21C8.15685 21 9.5 19.6569 9.5 18C9.5 16.3431 8.15685 15 6.5 15C4.84315 15 3.5 16.3431 3.5 18C3.5 19.6569 4.84315 21 6.5 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M18.5 19C20.1569 19 21.5 17.6569 21.5 16C21.5 14.3431 20.1569 13 18.5 13C16.8431 13 15.5 14.3431 15.5 16C15.5 17.6569 16.8431 19 18.5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </div>
                                                    <div class="control-panel-info">
                                                        <span class="h4 authorization-list-card__title">example.wav</span>
                                                        <span class="authorization-list-card__desc"><span>125 kb</span> / 1 MB</span>
                                                        <div class="progress__bar">
                                                            <div class="progress__line" style="width:88%"></div>
                                                        </div>
                                                        <span class="control-panel-percent"><span>88</span>%</span>
                                                        <i class="control-panel-delete icon-close">
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

                            </div>--}}

                            <label class="toggle mb-24">
                                <input class="toggle-checkbox" id="status" name="status" type="checkbox" value="1" {{ @$episode['status'] == 2 ? 'checked' : 0}}>
                                <span class="toggle-switch"></span>
                                <span class="toggle-label">{{ __('client.published') }}</span>
                            </label>

                            @if ($action == 'edit')
                                <div class="input__actions mt-0">
                                    <button class="btn btn_secondary btn_large" type="button">{{ __('client.delete') }}</button>
                                    <button class="btn btn_primary btn_large" type="submit">{{ __('client.save') }}</button>
                                </div>
                            @endif
                            @if ($action == 'add')
                                <button class="btn btn_default btn_primary" type="submit">{{ __('client.save') }}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </section>
        </main>

        <script>

        (function(){
            let $form = $('[data-episode-form]');
            $(document).on('change', '[data-episode-form] input[name="source"]', function(){
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
                $form.find('input[name="source"]').val('');
                $form.find('input[name="file_remove"]').val(1);
            });

        })(jQuery)

    </script>
@endsection
