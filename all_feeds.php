<?php


$servername =  "localhost"; //"103.14.120.241";
$username = "peeps0586_feeddb";
$password = "Peeps2020";
$dbname = "peeps0586_feeddb";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	    

$sql_event_details = " SELECT * FROM Feed 
LEFT JOIN User ON Feed.feedUserID = User.userID 
LEFT JOIN Post ON Feed.feedPostID = Post.postID  
LEFT JOIN Poll ON Feed.feedPollID = Poll.pollID ";	

//$sql_event_details = "SELECT * FROM Post";

//mysqli_set_charset($conn,"utf8");

//$result=mysql_query($sql_event_details);
$result = $conn->query($sql_event_details);

$num = $result->num_rows;


if ($result->num_rows > 0) {
while ($fetch_data = $result->fetch_assoc()) {
  
//Profile Pic

$profilePicId =  $fetch_data['userProfilePicId'];


//Cover pic         
$coverPicID=  $fetch_data['coverPicID'];


//Post
$postID =  $fetch_data['postID'];
$postMessage =  $fetch_data['postMessage'];
$postLink =  $fetch_data['postLink'];
$postLat =  $fetch_data['postLatitude'];

$postLong =  $fetch_data['postLongitude'];
//event related
$postContact =  $fetch_data['postContact'];
$postEventStart =  $fetch_data['postEventStart'];
$postEventEnd =  $fetch_data['postEventEnd'];
$postEventHost =  $fetch_data['postEventHost'];
//entertainment
$postTitle = $fetch_data['postTitle'];
//Lost Found
$postLostFoundType = $fetch_data['postLostFoundType'];
$postLostFoundName = $fetch_data['postLostFoundName'];
$postLostFoundAge = $fetch_data['postLostFoundAge'];
$postLostFoundGender = $fetch_data['postLostFoundGender'];



//Poll
$pollID =  $fetch_data['pollID'];
$pollQuestion =  $fetch_data['pollQuestion'];
$pollTotalVotes =  $fetch_data['pollTotalVotes'];
$pollIsVoted =  $fetch_data['isVoted'];

//Media
$mediaId = $fetch_data['mediaId'];
$mediaThumbUrl =  $fetch_data['mediaThumbUrl'];
$mediaUrl =  $fetch_data['mediaUrl'];

//Creator
$creatorArray = $fetch_data['emailid'];
$creatorFullName =  $fetch_data['firstName'];
$creatorDOB  =  $fetch_data['dob'];
$creatorContactNo  =  $fetch_data['mobileNo'];
$creatorProfilePicId  =  $fetch_data['userProfilePicId'];

//Feed
$feedType = $fetch_data['feedType'];
$feedId = $fetch_data['feedID'];
$feedCommentCount = $fetch_data['feedCommentCount'];
$feedCreatedAt = $fetch_data['feedCreatedAt'];
$feedLikeCount = $fetch_data['feedLikeCount'];
$feedNormalPostID = $fetch_data['feedPostID'];
$feedCreatorID = $fetch_data['userID'];
$feedEntityID = $fetch_data['feedLevel'];

//user profile pic details
$sql_for_user_pic = "SELECT * FROM Media WHERE Media.mediaId = ".$profilePicId;
$row = $conn->query($sql_for_user_pic);

$fetch_data_user_pic = $row->fetch_assoc();

$profilePicUrl = $fetch_data_user_pic['mediaUrl'];

//echo "\n Profile Pic URL -> ".$profilePicUrl."\n";

//post media select
$sql_post = "SELECT * FROM Media WHERE Media.postId = ".$postID;
$uni = array();	
$result_post_media = $conn->query($sql_post);
//$num_post_media = $result_post_media->num_rows;

if ($result_post_media->num_rows > 0) {
while ($row = $result_post_media->fetch_assoc()){

$mediaId = $row['mediaId'];
$mediaThumbUrl = $row['mediaThumbUrl'];
$mediaUrl = $row['mediaUrl'];
$uni[] = array("MediaId"=>$mediaId, "MediaUrl"=>$mediaUrl, "MediaThumpUrl" =>$mediaThumbUrl);
}}


$json_media = $uni;


//poll options select
$sql_poll_options = "SELECT * FROM pollOptions WHERE pollOptions.pollID = ".$pollID;
$options = array();
$result_poll_options = $conn->query($sql_poll_options);
$num_poll_options = $result_poll_options->num_rows;


if ($result_poll_options->num_rows > 0) {
while($row_poll_option = $result_poll_options->fetch_assoc()){
$pollOptionID = $row_poll_option['pollOptionID'];
$pollOpenText = $row_poll_option['optionText'];
$pollOptionMedia = $row_poll_option['mediaID'];

//fetching mediaUrl
$sql_for_option_pic = "SELECT * FROM Media WHERE Media.mediaId = ".$pollOptionMedia;

$row = $conn->query($sql_for_option_pic);

$optionPicUrl = $row->fetch_assoc()['mediaUrl'];

$options[] = array("pollOptionID"=>$pollOptionID, "pollOptionText"=>$pollOpenText, "pollOptionMediaUrl" =>$optionPicUrl);

}}

//echo json_encode($options);
$json_poll_options = $options;


            $json["Feeds"][] = [
              
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
                 'postLat'=>$postLat,
                 'postLong'=>$postLong,  
                 'postContact'=>$postContact,
                 'postEventStart'=>$postEventStart,
                 'postEventEnd'=>$postEventEnd,
                 'postEventHost'=>$postEventHost,
                 'postTitle'=>$postTitle,   
                 'postLostFoundType'=>$postLostFoundType,
                 'postLostFoundName'=>$postLostFoundName,
                 'postLostFoundAge'=>$postLostFoundAge,
                 'postLostFoundGender'=>$postLostFoundGender,           
                 'Media'=>$json_media],
                 'Poll'=>[
                 'pollId'=>$pollID,
                 'pollQuestion'=> $pollQuestion, 
                 'pollTotalVotes'=> $pollTotalVotes,
                 'pollIsVoted'=>$pollIsVoted,
                 'pollOptions'=>$json_poll_options
                 ]            
                ];

}
}
echo json_encode($json);


?>