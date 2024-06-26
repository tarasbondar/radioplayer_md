<div class="modal modal-filter fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="h3 modal-title text-center" id="exampleModalLabel">{{ __('client.categories') }}</h3>
                <button type="button" class="btn-close btn btn_ico" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}">
                    <svg class="icon">
                        <use href="/img/sprite.svg#x"></use>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="list">
                    @foreach($categories as $c)
                        <div class="input input__inner">
                            <input class="input__checkbox input__checkbox-podcasts" type="checkbox" id="category-{{$c['id']}}" value="{{$c['id']}}">
                            <label class="input__label light" for="category-{{$c['id']}}">
                                {{ $c->getTranslation('title') }}
                            </label>
                            <svg class="icon"><use href="/img/sprite.svg#check"></use></svg>
                            <div class="messages"></div>
                        </div>
                    @endforeach
                </div>
                <div class="form-actions">
                    <button class="btn btn_default btn_primary" data-bs-dismiss="modal">{{ __('app.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
