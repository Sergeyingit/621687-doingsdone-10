'use strict';

var $checkbox = document.getElementsByClassName('show_completed');

if ($checkbox.length) {
  $checkbox[0].addEventListener('change', function (event) {
    var is_checked = +event.target.checked;

    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set('show_completed', is_checked);

    window.location = '/index.php?' + searchParams.toString();
  });
}

var tasksCheckbox = document.querySelectorAll('.tasks');

if (tasksCheckbox.length) {
  for (var i = 0; i < tasksCheckbox.length; i++){
    tasksCheckbox[i].addEventListener('change', function (evt) {
    var isChecked = (evt.target.checked) ? '1' : '';
    var taskId = evt.target.getAttribute('value');
    var searchParams = new URLSearchParams(window.location.search);

    searchParams.set('task_id', taskId);
    searchParams.append('completed', isChecked);
    window.location = '/index.php?' + searchParams.toString();
    });
  }
}

flatpickr('#date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});
