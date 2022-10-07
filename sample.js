const body = new FormData()
body.append('action', 'bulk_update_acf_update')

const el_files = document.getElementById('bulk_update_acf_file')

el_files.addEventListener('change', (e) => {
  const file = e.target.files[0]
  body.append('userfile', file)
})

function post(url) {
  let request = new XMLHttpRequest
  request.open('POST', url, true)
  console.log('hoge')
  request.onreadystatechange = () => {
    console.log('fuga')
    if (request.readyState == 4 && request.status == 200) {
      addMessgae('bulk_update_acf_success', request.responseText)
    }
    if (request.readyState == 4 && request.status !== 200) {
      addMessgae('bulk_update_acf_error', request.responseText)
    }
  }
  request.send(body)
}

function addMessgae(element_id, response_text) {
  const el = document.getElementById(element_id)
  el.removeAttribute('hidden')
  el.insertAdjacentHTML('beforeend', `<p>${response_text}<p/>`)
}
