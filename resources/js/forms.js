import validate from "validate.js";

export function forms() {
    const fileInput = document.querySelectorAll('[data-file_input]');

    if (fileInput) {
        fileInput.forEach(function (e) {
            const outputId = e.getAttribute('data-file_input');
            // const output = document.getElementById(outputId);

            e.addEventListener('change', function (event) {
            	if (outputId === 'file-image') {
		            const [file] = event.target.files;
		            if (file) {
			            e.closest('.control-panel').classList.add('control-panel_preview');
			            e.closest('.file').querySelector('.authorization-list-card__preview').src = URL.createObjectURL(file);
		            }
	            }
                // else {
		        //     const files = event.target.files;
		        //     for (const file of files) {
			    //         output.value = file.name;
		        //     }
	            // }
            });
        });
    }

    const agree = document.querySelector('[data-agree]');
	if (agree) {
		agree.addEventListener('click', function () {
			const check = agree.checked;
			if (check === true) {
				agree.closest('form').querySelector('button[type="submit"]').removeAttribute('disabled');
			} else {
				agree.closest('form').querySelector('button[type="submit"]').setAttribute('disabled', '');
			}
		});
	}

	let ranges = document.querySelectorAll(".custom-range");
	let base = window.getComputedStyle(document.body).getPropertyValue("--base");
	let second = window.getComputedStyle(document.body).getPropertyValue("--second");

	let init = (value, el) => {
		el.style.setProperty("--range", value + "%");
	};
	let updateVar = (value, el) => {
		el.style.setProperty("--range", value + "%");
	};
	for (const range of ranges) {
		init(range.value, range);
		range.addEventListener("input", () => {
			updateVar(range.value, range);
		});
	}

    // https://validatejs.org/
    (function() {
        validate.validators.checked = function(value, options) {
            if (value !== true) return options.message || ' is required';
        };

        const form = document.querySelectorAll('[data-validate]');
        form.forEach(function (e) {
            var form = e;
            var formId = form.getAttribute('id');
            var constraints = {};

            switch (formId) {
                case 'apply-form':
                    constraints = {
                        name: {
                            presence: true,
                        },
                        email: {
                            presence: true,
                            email: true
                        },
                        agree: {
                            checked: true
                        },
                        phone: {
                            presence: true,
                        },
                        position: {
                            presence: true,
                            exclusion: {
                                within: {default: "Choose one"},
                                message: ' is required'
                            }
                        },
                        letter: {
                            // presence: false,
                        },
                        cv: {
                            // presence: false,
                        }
                    };
                    break;

                case 'contact-form':
                    constraints = {
                        name: {
                            presence: true,
                        },
                        email: {
                            presence: true,
                            email: true
                        },
                        agree: {
                            checked: true
                        },
                        message: {
                            presence: true,
                        },
                        subject: {
                            presence: true,
                        }
                    };
                    break;

                case 'request-form':
                    constraints = {
                        name: {
                            presence: true,
                        },
                        email: {
                            presence: true,
                            email: true
                        },
                        agree: {
                            checked: true
                        },
                        message: {
                            presence: true,
                        }
                    };
                    break;

                case 'search-form':
                    constraints = {
                        search: {
                            presence: true,
                        },
                    };
                    break;

                default:
                    break;
            }


            form.addEventListener('submit', function (event) {
                event.preventDefault();
                handleFormSubmit(form);
            });

            watchInputs(form);


            function watchInputs(form) {
                var inputs = form.querySelectorAll('input, textarea, select');
                for (var i = 0; i < inputs.length; ++i) {
                    inputs.item(i).addEventListener('change', function (event) {
                        var errors = validate(form, constraints) || {};
                        showErrorsForInput(this, errors[this.name]);
                    });
                }
            }

            function handleFormSubmit(form, input) {
                var errors = validate(form, constraints);
                showErrors(form, errors || {});
                if (!errors) {
                    showSuccess(form);
                }
            }

            function showErrors(form, errors) {
                var inputEls = form.querySelectorAll('input[name], select[name], textarea[name]');
                inputEls.forEach(function(el){
                    showErrorsForInput(el, errors && errors[el.name]);
                })
            }

            function showErrorsForInput(input, errors) {
                var formGroup = closestParent(input.parentNode, 'input');
                if (formGroup) {
	                var messages = formGroup.querySelector('.messages');

	                resetFormGroup(formGroup);

	                if (errors) {
		                formGroup.classList.add('has-error');

		                errors.forEach(function(error){
			                addError(messages, error);
		                });
	                } else {
		                formGroup.classList.add('has-success');
	                }
                }
            }

            // Recusively finds the closest parent that has the specified class
            function closestParent(child, className) {
                if (!child || child == document) {
                    return null;
                }
                if (child.classList.contains(className)) {
                    return child;
                } else {
                    return closestParent(child.parentNode, className);
                }
            }

            function resetFormGroup(formGroup) {
                // Remove the success and error classes
                formGroup.classList.remove('has-error');
                formGroup.classList.remove('has-success');

                var errorEls = formGroup.querySelectorAll('.help-block.error');
                errorEls.forEach(function(el){
                    el.parentNode.removeChild(el);
                });
            }

            // Adds the specified error with the following markup
            // <p class="help-block error">[message]</p>
            function addError(messages, error) {
                var block = document.createElement('p');
                block.classList.add('help-block');
                block.classList.add('error');
                block.innerText = error;
                messages.appendChild(block);
            }

            function showSuccess(form) {
                var success_msg = form.querySelector('[data-validate_success]');

                if (success_msg) {
                    form.querySelector('[data-validate_body]').classList.add('hidden');
                    success_msg.classList.add('active');
                }

            }

        });

    })();
}
