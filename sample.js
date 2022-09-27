//const file = document.getElementById('bu_acf_file');
//input.addEventListener('change', fileInput);
const body = new FormData();

const file = document.getElementById('bu_acf_file');
file.addEventListener('change', (e) => {
  const hoge = e.target.files[0]
  body.append('userfile', hoge);
  body.append('action', 'bu_acf_article_update');
  body.append('MAX_FILE_SIZE', '300000');
  console.log(body)
})

function post (url){
  let request =  new XMLHttpRequest;
  console.log('JS:ぽよ');
  request.open ('POST', url, true);
  request.onreadystatechange =()=> {
    if (request.readyState < 4){
      console.log('JS:送信中');
    }
    if(request.readyState == 4 && request.status == 200) {
      console.log('JS:' + request.responseText);
      console.log('JS:完了しました');
    }
    if(request.readyState == 4 && request.status !== 200) {
      console.log('JS:' + request.responseText);
      console.log('JS:ERROR');
    }
  }
  console.log(body)
  request.send(body);
}
