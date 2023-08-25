$(document).ready(() => {
    
    $('.txtEmail').attr('placeholder', '@');
    $('.txtAddress').attr('placeholder', 'Street name and number');
    $('.txtPhone').mask('000-000-0000', { placeholder: '___-___-____' });

    // ABCEGHJKLMNPRSTVXY][0-9][ABCEGHJKLMNPRSTVXY])([0-9][ABCEGHJKLMNPRSTVXY][0-9]

    $('.txtPostalCode').mask('A0A 0A0',
    {
      placeholder: 'A1A 1A1',
      translation: {
        A: { pattern: /[ABCEGHJKLMNPRSTVXYabceghjklmnprstvxy]/ }
      }
    });

  $('.txtPostalCode').keyup(function () {
    $(this).val($(this).val().toUpperCase());
  });

    const registrationForm = document.querySelectorAll('.needs-validation-register');
    addValidationToForm(registrationForm);
});

function addValidationToForm(forms) {
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        });
}