var countdown = Date.now(),
    currentTime = Date.now();

$('#loading').show();
ChangeExample('tianlongbin2');
setTimeout(function() {
  airplayIn();
}, 5000);
$(document).ready(function()
{
  $('.ellipsis').dotdotdot();
  $('.tip').simpletooltip({
    position: 'left-top'
  });

  $('#normalSubmit').click(function() {
    // slapFaceAudio();
    $('#directpost').val('');
    $('#coverForm').attr("target","_self").submit();
  });

  $('#facebookSubmit').click(function() {
    // slapFaceAudio();
    $('#directpost').val('1');
    $('#coverForm').attr("target","_blank").submit();
  });

  // $('#contribute_submit').click(function() {
  //   mailSendAudio();
  //   sendContribute();
  // });

  window.setInterval(shack, 10000);

  $('.contribute').click(function(){
    $('body').css('overflow','hidden');
    $('.simple-tooltip').hide();
    airplayOut();
  });

  // $('#close').click(function(){
  //   $('body').css('overflow','auto');
  //     $('#hideForm').animate({
  //     height: "0",
  //     padding: "0 20px"
  //   }, 200).fadeOut();
  //   airplayReset();
  //   airplayIn();
  // });

  $("#hidebar").toggle(
    function() {
      $("#surprise").css("margin-bottom","0");
    },
    function() {
      $("#surprise").css("margin-bottom","-111px");
    }
  );
});

//uploader
$(function(){
  $('#coverbox').click(function(){
      $('#uploadInput').click();
  });
  $('#upload').fileupload({
    dropZone: $('.drop'),
    add: function (e, data) {
      if($("#role option[value='custom']").length == 0) {
        $('#role').append('<option value="custom">使用者上傳</option>');
      }
      ChangeExample('custom');
      $('#loading').show();
      var source = encodeURIComponent(data.files[0].name);
      var now = Math.round((new Date()).getTime() / 1000);
      var ext = source.split('.').pop().toLowerCase();
      var filename = now + "_" + $.base64.encode(source) + "." + ext;
      $('#filename').attr('value',filename);
      $('#source').attr('value',filename);
      var jqXHR = data.submit();
    },
    done: function (e, data) {
      filename=$('#filename').val();
      $('#coverprint').css('backgroundImage','url(upload/'+filename+')');
      setTimeout(function() {
        $('#role').val('custom');
        createImage();
      }, 5);
    }
  });
  $(document).on('drop dragover', function (e) {
      e.preventDefault();
  });
});

$('body').delegate('#text1, #text2, #text3', 'blur', function() {
  createImage();
});
$('body').delegate('#text1, #text2, #text3', 'keydown', function() {
  countdown = Date.now();
});
$('body').delegate('#text1, #text2, #text3', 'keyup', function() {
  setTimeout(function(){
    currentTime = Date.now();
    if((currentTime - countdown) >= 240 ) {
      $('#loading').show();
      createImage();
    }
  }, 250);
});
$('body').delegate('#role', 'change', function() {
  $('#loading').show();
  slapFaceAudio();
  ChangeExample($("#role").val());
});

function createImage(){
  $.ajax({
    url: 'ajax',
    dataType: 'html',
    type:'POST',
    data: {
      text1: $("#text1").val(),
      text2: $("#text2").val(),
      text3: $("#text3").val(),
      role: $("#role").val(),
      source: $("#source").val()
    },
    success: function(response){
      $('#coverprint').html(response).promise().done(function(){
        $('#loading').hide();
      });
    }
  });
}
function sendContribute(){
  $.ajax({
    url: 'contribute',
    dataType: 'html',
    type:'POST',
    data: {
      text1: $("#contribute_text1").val(),
      text2: $("#contribute_text2").val(),
      text3: $("#contribute_text3").val(),
      role: $("#contribute_role").val(),
      egg: $("#contribute_egg").val()
    },
    success: function(response){
      $('body').css('overflow','auto');
        $('#hideForm').animate({
        height: "0",
        padding: "0 20px"
      }, 200).fadeOut();
      airplayReset();
      airplayIn();
      $("#contribute_text1").val(null);
      $("#contribute_text2").val(null);
      $("#contribute_text3").val(null);
      $("#contribute_role").val(null);
      $("#contribute_egg").val(null);
      $('#pop').fadeIn().delay(1000).fadeOut();
    }
  });
}
function ChangeExample(role){
  if(role!=='custom')
    $('#coverprint').css('backgroundImage','url(images/role/'+role+'.jpg)');
  $.ajax({
    url: 'example',
    dataType: 'html',
    type:'POST',
    data: {
      role: role
    },
    success: function(response){
      $('#example').html(response).promise().done(function(){
        createImage();
      });
    }
  });
}

//audio
function slapFaceAudio(){
  var iframe='<object width="560" height="315"><param name="movie" value="//www.youtube.com/v/5STx1cHPhYc?version=3&amp;hl=zh_TW&amp;rel=0&autoplay=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="//www.youtube.com/v/5STx1cHPhYc?version=3&amp;hl=zh_TW&amp;rel=0&autoplay=1" type="application/x-shockwave-flash" width="560" height="315" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
  $('#audio').html(iframe);
}
function mailSendAudio(){
  var iframe='<object width="560" height="315"><param name="movie" value="//www.youtube.com/v/XW-dpT0jEcQ?version=3&amp;hl=zh_TW&amp;rel=0&autoplay=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="//www.youtube.com/v/XW-dpT0jEcQ?version=3&amp;hl=zh_TW&amp;rel=0&autoplay=1" type="application/x-shockwave-flash" width="560" height="315" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
  $('#audio').html(iframe);
}

//airplan
function airplayIn(){
  $('.contribute').show().animate({
    bottom: "10px",
    right: "20px"
  }, 1000);
}
function airplayOut(){
  $('.contribute').animate({
    bottom: "50%",
    right: "50%"
  }, 500, function() {
      $(this).fadeOut();
      $('#hideForm').show().animate({
      height: "80%",
      padding: "20px"
    }, 500);
    });
}
function airplayReset(){
  $('.contribute').css('bottom','-60px').css('right','-60px');
}
function shack(){
  $('.contribute')
  .animate({bottom: "20px",right: "30px"}, 100)
  .animate({bottom: "10px",right: "20px"}, 100)
  .animate({bottom: "20px",right: "30px"}, 100)
  .animate({bottom: "10px",right: "20px"}, 100);
}

$(window).scroll(function (event) {
  var scroll = $(window).scrollTop();
  var height = $(window).height();
  if(scroll > height*0.5)
    $('.gototop').show();
  else
    $('.gototop').hide();
});
$('.gototop').click(function(){
  $('html,body').animate({scrollTop: 0},'fast');
});
