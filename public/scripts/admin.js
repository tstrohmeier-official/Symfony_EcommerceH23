const addFormToCollection = (e) => {
  const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

  $(document).ready(() => {
    const registrationForm = document.querySelectorAll('.needs-validation-categories');
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

  const item = document.createElement('li');
  item.className = 'col-5 m-1';

  item.innerHTML = collectionHolder
    .dataset
    .prototype
    .replace(
      /__name__/g,
      collectionHolder.dataset.index
    );



  collectionHolder.appendChild(item);

  collectionHolder.dataset.index++;
};

document
  .querySelectorAll('.add_item_link')
  .forEach(btn => {
    btn.addEventListener("click", addFormToCollection)
  });

