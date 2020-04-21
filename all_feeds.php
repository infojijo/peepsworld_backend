<?php

$servername =  "sql101.byethost7.com"; //"103.14.120.241";
$username = "b7_25573968";
$password = "Peeps@2020";
$dbname = "b7_25573968_peeps_db";


mysql_connect($servername,$username,$password);
@mysql_select_db($dbname) or die( "Unable to select database");

$sql_event_details = " SELECT * FROM Feed 
        LEFT JOIN User ON Feed.feedUserID = User.userID 
        LEFT JOIN Post ON Feed.feedPostID = Post.postID
        LEFT JOIN Poll ON Feed.feedPollID = Poll.pollID
        ";	

//mysqli_set_charset($conn,"utf8");
$result=mysql_query($sql_event_details);
$num = mysql_num_rows($result);  

$i = $num-1;
while ( $i > -1) {{
  
//Profile Pic
$profilePicId =  mysql_result($result,$i,'userProfilePicId');

//Cover pic         
$coverPicID=  mysql_result($result,$i,'coverPicID');

//Post
$postID =  mysql_result($result,$i,'postID');
$postMessage =  mysql_result($result,$i,'postMessage');
$postLink =  mysql_result($result,$i,'postLink');
$postMedia =  mysql_result($result,$i,'postMedia');

//Poll
$pollID =  mysql_result($result,$i,'pollID');
$pollQuestion =  mysql_result($result,$i,'pollQuestion');
$pollTotalVotes =  mysql_result($result,$i,'pollTotalVotes');
$pollIsVoted =  mysql_result($result,$i,'isVoted');

//Media
$mediaId = mysql_result($result,$i,'mediaId');
$mediaThumbUrl =  mysql_result($result,$i,'mediaThumbUrl');
$mediaUrl =  mysql_result($result,$i,'mediaUrl');

//Creator
$creatorArray = mysql_result($result,$i,'emailid');
$creatorFullName =  mysql_result($result,$i,'firstName');
$creatorDOB  =  mysql_result($result,$i,'dob');
$creatorContactNo  =  mysql_result($result,$i,'mobileNo');
$creatorProfilePicId  =  mysql_result($result,$i,'userProfilePicId');

//Feed
$feedType = mysql_result($result,$i,'feedType');
$feedId = mysql_result($result,$i,'feedID');
$feedCommentCount = mysql_result($result,$i,'feedCommentCount');
$feedCreatedAt = mysql_result($result,$i,'feedCreatedAt');
$feedLikeCount = mysql_result($result,$i,'feedLikeCount');
$feedNormalPostID = mysql_result($result,$i,'feedPostID');
$feedCreatorID = mysql_result($result,$i,'userID');
$feedEntityID = mysql_result($result,$i,'feedLevel');

//user profile pic details
$sql_for_user_pic = "SELECT * FROM Media WHERE Media.mediaId = ".$profilePicId;
$row = mysql_query($sql_for_user_pic);
$profilePicUrl = mysql_result($row,0,'mediaUrl');

//post media select
$sql_post = "SELECT * FROM Media WHERE Media.postId = ".$postID;
$uni = array();	
$result_post_media = mysql_query($sql_post);
$num_post_media = mysql_num_rows($result_post_media);

$j = $num_post_media-1;

while($j>-1){{
$mediaId = mysql_result($result_post_media,$j,'mediaId');
$mediaThumbUrl = mysql_result($result_post_media,$j,'mediaThumbUrl');
$mediaUrl = mysql_result($result_post_media,$j,'mediaUrl');

$uni[] = array("MediaId"=>$mediaId, "MediaUrl"=>$mediaUrl, "MediaThumpUrl" =>$mediaThumbUrl);

}
    $j--;
}
$json_media = $uni;

//poll options select
$sql_poll_options = "SELECT * FROM pollOptions WHERE pollOptions.pollID = ".$pollID;
$options = array();
$result_poll_options = mysql_query($sql_poll_options);
$num_poll_options = mysql_num_rows($result_poll_options);

$k = $num_poll_options-1;

while($k>-1){{
$pollOptionID = mysql_result($result_poll_options,$k,'pollOptionID');
$pollOpenText = mysql_result($result_poll_options,$k,'optionText');
$pollOptionMedia = mysql_result($result_poll_options,$k,'mediaID');

//fetching mediaUrl

$sql_for_option_pic = "SELECT * FROM Media WHERE Media.mediaId = ".$pollOptionMedia;
$row = mysql_query($sql_for_option_pic);
$optionPicUrl = mysql_result($row,0,'mediaUrl');

$options[] = array("pollOptionID"=>$pollOptionID, "pollOpenText"=>$pollOpenText, "pollOptionMediaUrl" =>$optionPicUrl);

}
    $k--;
}
$json_poll_options = $options;


            $json["Response"][] = [
                 'feed' => [
                 'feedType'=> $feedType,
                 'feedID' => $feedId,
                 'feedLevel' => $feedEntityID,
                 'feedCommentCount'=>$feedCommentCount,
                 'feedCreatedAt'=>$feedCreatedAt,
                 'feedLikeCount'=>$feedLikeCount,
                 'feedPostID'=>$feedNormalPostID,                 
                 'creator'=>[
                 'creatorID'=>$feedCreatorID,
                 'creatorEmail' => $creatorArray,
                 'creatorFullName' => $creatorFullName, 
                 'creatorDOB'=>$creatorDOB,
                 'creatorContactNo'=>$creatorContactNo,
                 'creatorFullName'=>$creatorFullName,
                 'profilePic'=>[
                 'profilePicId'=>$creatorProfilePicId,
                 'profilePicUrl'=>$profilePicUrl]],
                 'Post'=>[
                 'postId'=>$postID,
                 'postMessage'=> $postMessage, 
                 'postLink'=>$postLink,
                 'Media'=>$json_media],
                 'Poll'=>[
                 'pollId'=>$pollID,
                 'pollQuestion'=> $pollQuestion, 
                 'pollTotalVotes'=> $pollTotalVotes,
                 'pollIsVoted'=>$pollIsVoted,
                 'pollOptions'=>$json_poll_options
                 ]
                
                ]
                ];

            }  
    $i--;
}
    
echo json_encode($json);

?>