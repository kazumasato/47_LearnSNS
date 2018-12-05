$(function() {
  $(document).on('click', '.js-like', function() {
  var feed_id = $(this).siblings('.feed-id').text();
    var user_id = $('.signin-user').text();

    console.log(feed_id);
    console.log(user_id);

    $.ajax({
      // 送信先や送信するデータなど
      url: 'like.php',
      type: 'POST',
      datatype: 'json',
      data: {
        'feed_id': feed_id,
        'user_id': user_id
      }
    }).done(function (data) {
      // 成功時の処理
      console.log(data);
    }).fail(function (e) {
      // 失敗時の処理
      console.log(e);
    })

  });
});