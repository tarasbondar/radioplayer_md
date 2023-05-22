@extends('layouts/client')

@section('content')

    <main class="main">
        <div class="container">
            <div class="settings">
                <h2 class="h2 settings__title">{{ __('user.settings')  }}</h2>

                <div class="input">
                    <button class="btn btn_fake-input" type="button" aria-expanded="false" data-bs-toggle="modal"
                            data-bs-target="#profileModal">
                        <span class="small">{{ __('user.profile')  }}</span>
                        <span class="h3">{{ auth()->user()->name  }}</span>
                        <span class="small">{{ auth()->user()->email  }}</span>
                        <span class="btn btn-modal-toggle">
                            <svg class="icon">
                                <use href="/img/sprite.svg#chevron-right"></use>
                            </svg>
                        </span>
                    </button>
                </div>

                <div class="input">
                    <button class="btn btn_fake-input" type="button" aria-expanded="false" data-bs-toggle="modal"
                            data-bs-target="#securityModal">
                        <span>{{ __('user.password_change')  }}</span>
                        <span class="btn btn-modal-toggle">
                            <svg class="icon">
                                <use href="/img/sprite.svg#chevron-right"></use>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>


        </div>
    </main>

    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <form class="modal-content" action="{{ route('profile.changeName') }}" data-ajax-form method="post">
                @csrf
                <div class="modal-header">
                    <h3 class="h3 modal-title text-center" id="profileModalLabel">{{ __('user.profile')  }}</h3>
                    <button type="button" class="btn-close btn btn_ico" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="icon">
                            <use href="/img/sprite.svg#x"></use>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input">
                        <div class="input__inner form-floating">
                            <input type="text" class="form-control" placeholder="{{ __('user.name')  }}"
                                   id="profile-name" name="name" value="{{ auth()->user()->name  }}">
                            <label for="profile-name">{{ __('user.name')  }}</label>
                        </div>
                        <div class="input__desc x-small">{{ __('user.name_hint')  }}</div>
                        <div class="messages" data-for="name"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn_default btn_primary" type="submit">{{ __('form.save')  }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <form class="modal-content" action="{{ route('profile.changePassword') }}" method="post" data-ajax-form>
                <div class="modal-header">
                    <h3 class="h3 modal-title text-center" id="securityModalLabel">{{ __('user.security')  }}</h3>
                    <button type="button" class="btn-close btn btn_ico" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="icon">
                            <use href="/img/sprite.svg#x"></use>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input form-floating">
                        <input type="password" class="form-control" placeholder="{{ __('user.password_old')  }}"
                               id="security-old-password" name="password_old">
                        <label for="security-old-password">{{ __('user.password_old')  }}</label>
                        <div class="messages" data-for="password_old"></div>
                    </div>

                    <div class="input form-floating">
                        <input type="password" class="form-control" placeholder="{{ __('user.password_new')  }}"
                               id="security-new-password" name="password_new">
                        <label for="security-new-password">{{ __('user.password_new')  }}</label>
                        <div class="messages" data-for="password_new"></div>
                    </div>

                    <div class="input form-floating">
                        <input type="password" class="form-control" placeholder="{{ __('user.password_new_confirmation')  }}"
                               id="security-repeat-new-password" name="password_new_confirmation">
                        <label for="security-repeat-new-password">{{ __('user.password_new_confirmation')  }}</label>
                        <div class="messages" data-for="password_new_confirmation"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn_default btn_primary" type="submit">{{ __('form.save')  }}</button>
                </div>
            </form>
        </div>
    </div>


    <script>

        (function () {
            $(document).ready(function(){
                $(document).on('submit', '[data-ajax-form]', function(){
                    let form = $(this);
                    let url = form.attr('action');
                    let method = form.attr('method');
                    let data = form.serialize();
                    $('[data-for]').html('');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: method,
                        url: url,
                        data: data,
                        success: function (response) {
                            if (response.status === 'success') {
                                if (typeof response.redirectUrl != 'undefined') {
                                    window.location.href = response.redirectUrl;
                                }
                            } else {
                                $.each(response.errors, function (key, value) {
                                    form.find('[data-for="' + key + '"]').html(value[0]);
                                });
                            }
                        }
                    });
                    return false;
                });
            });
        })(jQuery)

    </script>

@endsection
