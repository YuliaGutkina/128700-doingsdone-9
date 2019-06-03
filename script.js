'use strict';

var get_parameter_names = [
  'id',
  'date',
  'search'
];

var $checkbox = document.getElementsByClassName('show_completed');

if ($checkbox.length) {
  $checkbox[0].addEventListener('change', function (event) {
    var is_checked = +event.target.checked;
    var location = '/index.php?show_completed=' + is_checked;

    get_parameter_names.forEach(name => {
      var parameter = findGetParameter(name);

      if (parameter !== null) {
        location += '&' + name + '=' + parameter
      }
    });

    window.location = location
  })
}

var $taskCheckboxes = document.getElementsByClassName('tasks');

if ($taskCheckboxes.length) {

  $taskCheckboxes[0].addEventListener('change', function (event) {
    if (event.target.classList.contains('task__checkbox')) {
      var el = event.target;

      var is_checked = +el.checked;
      var task_id = el.getAttribute('value');

      var url = '/index.php?task_id=' + task_id + '&check=' + is_checked;
      window.location = url
    }
  })
}

flatpickr('#date', {
  enableTime: false,
  dateFormat: 'd.m.Y',
  time_24hr: true,
  locale: 'ru'
});

function findGetParameter (parameterName) {
  var result = null,
    tmp = [];
  location.search
    .substr(1)
    .split('&')
    .forEach(function (item) {
      tmp = item.split('=');
      if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1])
    });
  return result
}
