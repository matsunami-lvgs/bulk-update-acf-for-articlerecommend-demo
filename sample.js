function post (url){
  let request =  new XMLHttpRequest;
  console.log('ぽよ');
  request.open ('POST', url, true);
  request.setRequestHeader("Content-Type", "multipart/form-data");
  request.onreadystatechange =()=> {
    if (request.readyState < 4){
      console.log('送信中');
    }
    if(request.readyState == 4 && request.status == 200) {
      console.log(request.responseText);
      console.log('完了しました');
    }
    if(request.readyState == 4 && request.status !== 200) {
      console.log(request.responseText);
      console.log('ERROR');
    }
  }
  console.log(request.getAllResponseHeaders());
  request.send();
}
