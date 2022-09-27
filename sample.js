const body = new FormData();
body.append('action', 'bu_acf_article_update')

const el_files = document.getElementById('bu_acf_file');

el_files.addEventListener('change', (e) => {
  const file = e.target.files[0]
  body.append('userfile', file);
})

function post(url) {
  let request = new XMLHttpRequest;
  request.open('POST', url, true);
  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      addMessgae('bu_acf_updated', request.responseText);
    }
    if (request.readyState == 4 && request.status !== 200) {
      addMessgae('bu_acf_error', request.responseText);
    }
  }
  request.send(body);
}

function addMessgae(element_id, response_text) {
  const el = document.getElementById(element_id);
  el.removeAttribute('hidden');
  el.insertAdjacentHTML('afterbegin', `<p>${response_text}<p/>`);
}
