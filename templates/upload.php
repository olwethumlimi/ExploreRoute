
<!-- <img src="<?php print($file); ?>"  width="100px"/> -->
<input type="file" id="file"  />



<button class="button">upload</button>
<script>
        $("button").click(e=>{
            var form= new FormData();
            var f=$("#file").prop("files")[0];
            form.append("this",f);
            $.ajax({
                url:"upload",
                method:"post",
                type:"post",
                cache:false,
                contentType:false,
                processData:false,
                data:form,
                success:e=>{
                console.log(e);
                }
            })
        })
</script>