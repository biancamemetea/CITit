<style>
.comment {
    /*display: inline-block;*/
    border: solid 2px black;
    border-radius: 5px;
    margin: 5px;
}

span {
    display: inline-flex;
}
</style>
<div style="font-size: 16px">
    <p>CONTENT : {ARTICLE_CONTENT}</p>
    <p>Created on : {ARTICLE_DATE}</p>
    <p>Article ID : {ARTICLE_ID}</p>
</div>
<div class="comment">
    <form method="POST">
        <input type="text" name="id" value="{ARTICLE_ID}" hidden="true">
        <textarea style="margin: 5px;" rows='3' cols='90' name="content"></textarea>
        <input style="margin: 5px;" type="submit" class="button">

    </form>
</div>
<hr>
    <h1>Comments:</h1>
<div>
    <!-- BEGIN comment_display -->
    <div id="comment_{COMMENT_ID}">
        <hr size="10">
            <div>
                <div id="appendTo{COMMENT_ID}">
                    <strong class="{COMMENT_ID}">{COMMENT_USERID} : </strong>
                    <br>
                    <span style="padding: 0px 20px;"  id="content{COMMENT_ID}" class="{COMMENT_ID}">{COMMENT_CONTENT}</span>
                    <!-- BEGIN comment_controls -->
                    <span>
                        <button id="edit{COMMENT_ID}" onclick="editComment({COMMENT_ID})">Edit</button>
                        <button id="delete{COMMENT_ID}" onclick="deleteComment({COMMENT_ID})">Delete</button>
                    </span>
                    <span id="save{COMMENT_ID}"></span>
                    <!-- END comment_controls -->
                </div>
                <br>
                replies:
                <br>
                <!-- BEGIN comment_reply -->
                    <div style="padding-left: 3%; margin: 7px">
                        <strong>-</strong>
                        <strong class="{REPLY_ID}">{REPLY_USERNAME} : </strong>
                        <span style="padding: 0px 20px;" class="{REPLY_ID}" id="content{REPLY_ID}">{REPLY_CONTENT}</span>
                        <!-- BEGIN reply_controls -->
                        <span>
                            <button id="edit{REPLY_ID}" onclick="editComment({REPLY_ID})">Edit</button>
                            <button id="delete{REPLY_ID}" onclick="deleteComment({REPLY_ID})">Delete</button>
                        </span>
                        <!-- END reply_controls -->
                        <br>
                        <span id="save{REPLY_ID}"></span>
                    </div>
                <!-- END comment_reply -->
            </div>
        <hr>
    </div>
    <!-- END comment_display -->
</div>

{PAGINATION}

<script>
function deleteArticle(id)
{
    if (confirm("Are you sure you want to delete the article?")) {

        var articleData = {};

        articleData = {
                id : id,
                delete : 1
        };
        // var articleData = [id, textareaValue];
        //console.log(articleData);
        $.ajax({
            type: 'POST',
            url:'{SITE_URL}/admin/article/delete_Replay',
            data: articleData,
            success: function (data) {
                // location.reload();
            }
        });
        $("#comment_"+id).remove();
    }
}

var siteUrl = "{SITE_URL}";
function deleteComment(id)
{
    // console.log(siteUrl + '/admin/article/json_delete_Comment');
    if (confirm("Are you sure you want to delete the comment?")) {
       // var commentData = {};
        commentData = {
                id : id,
                delete : 1
        };
        //console.log(commentData);
        $.ajax({
            type: 'POST',
            url:'{SITE_URL}/admin/article/delete_Replay',
            data: {id: id},
            //dataType:'JSON',
            success: function (stuff) {
                //alert('ok');
                //var myData = JSON.parse(stuff);
               // $("."+id).hide().html(myData.content).fadeIn('fast');
            }
        });
        $("#edit"+id).remove();
        $("#delete"+id).remove();
       //$("#appendTo"+id).remove();
    }
}
function editComment(id)
{
    var x = $("#content"+id).html();
    $("#content"+id).replaceWith(function() {
        return "<textarea class='textarea"+id+"' rows='2' cols='100'>" + $(this).val() + '</textarea>';
    });
    $(".textarea"+id).html(x);
    $("#edit"+id).hide();
    $("#delete"+id).hide();
    $('#save'+id).append("<button id = 'save_button_"+id+"' onclick='saveComment("+id+")'>Save</button>");
}
function saveComment(id)
{
    var commentData = {};
    var textareaValue = $(".textarea"+id).val();
    commentData = {
            id : id,
            content : textareaValue
    };
    $.ajax({
        type: 'POST',
        data: commentData,
        success: function (data) {
            $(".textarea"+id).replaceWith(function() {
                return '<span style="padding: 0px 20px;" id="content'+id+'">' + $(this).val() + '</div>';
            });
            $("#edit"+id).show();
            $("#delete"+id).show();
            $("#save_button_"+id).remove();
        }
    });
}
</script>