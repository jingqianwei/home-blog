<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        #progress{
            width: 300px;
            height: 20px;
            background-color: #f7f7f7;
            box-shadow:inset 0 1px 2px rgba(0,0,0,0.1);
            border-radius:4px;
            background-image:linear-gradient(to bottom,#f5f5f5,#f9f9f9);
        }

        #finish{
            background-color: #149bdf;
            background-image:linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);
            background-size:40px 40px;
            height: 100%;
        }
        form{
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div id="progress">
    <div id="finish" style="width: 0%;" progress="0"></div>
</div>
<form action="{{ route('home.file') }}">
    <input type="file" name="file" id="file">
    <input type="button" value="停止" id="stop">
</form>
<script type="application/javascript">
    let fileForm = document.getElementById("file");
    let stopBtn = document.getElementById('stop');
    let upload = new Upload();

    fileForm.onchange = function(){
        upload.addFileAndSends(this);
    }

    stopBtn.onclick = function(){
        this.value = "停止中";
        upload.stop();
        this.value = "已停止";
    }

    function Upload(){
        let xhr = new XMLHttpRequest();
        let form_data = new FormData();
        const LENGTH = 1024 * 1024;
        let start = 0;
        let end = start + LENGTH;
        let blob;
        let blob_num = 1;
        let is_stop = 0;

        //对外方法，传入文件对象
        this.addFileAndSends = function(that){
            let file = that.files[0];
            blob = cutFile(file);
            sendFile(blob,file);
            blob_num  += 1;
        }

        //停止文件上传
        this.stop = function(){
            xhr.abort();
            is_stop = 1;
        }

        //切割文件
        function cutFile(file){
            let file_blob = file.slice(start,end);
            start = end;
            end = start + LENGTH;
            return file_blob;
        }

        //发送文件
        function sendFile(blob,file){
            let total_blob_num = Math.ceil(file.size / LENGTH);
            form_data.append('file', blob);
            form_data.append('blob_num', blob_num);
            form_data.append('total_blob_num', total_blob_num);
            form_data.append('file_name', file.name);

            xhr.open('POST', "{{ route('home.file') }}",false);
            xhr.onreadystatechange  = function () {
                let progress;
                let progressObj = document.getElementById('finish');
                if(total_blob_num == 1){
                    progress = '100%';
                }else{
                    progress = Math.min(100,(blob_num/total_blob_num)* 100 ) +'%';
                }
                progressObj.style.width = progress;
                let t = setTimeout(function(){
                    if(start < file.size && is_stop === 0){
                        blob = cutFile(file);
                        sendFile(blob,file);
                        blob_num  += 1;
                    }else{
                        setTimeout(t);
                    }
                },1000);
            }

            xhr.send(form_data);
        }
    }

</script>
</body>
</html>
