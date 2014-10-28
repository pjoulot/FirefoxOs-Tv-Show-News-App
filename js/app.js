//action menu
document.querySelector('#btn-action-menu').addEventListener ('click', function () {
  document.querySelector('#action-menu').className = 'fade-in';
});
document.querySelector('#action-menu').addEventListener ('click', function () {
  this.className = 'fade-out';
});

//lists
document.querySelector('#btn-lists').addEventListener ('click', function () {
  document.querySelector('#lists').className = 'current';
  document.querySelector('[data-position="current"]').className = 'left';
});
document.querySelector('#btn-lists-back').addEventListener ('click', function () {
  document.querySelector('#lists').className = 'right';
  document.querySelector('[data-position="current"]').className = 'current';
});

//progress
document.querySelector('#btn-progress').addEventListener ('click', function () {
  document.querySelector('#progress').className = 'current';
  document.querySelector('[data-position="current"]').className = 'left';
});
document.querySelector('#btn-progress-back').addEventListener ('click', function () {
  document.querySelector('#progress').className = 'right';
  document.querySelector('[data-position="current"]').className = 'current';
});
