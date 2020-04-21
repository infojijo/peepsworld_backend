<?php

$servername =  "sql101.byethost7.com"; //"103.14.120.241";
$username = "b7_25573968";
$password = "Peeps@2020";
$dbname = "b7_25573968_peeps_db";


mysql_connect($servername,$username,$password);
@mysql_select_db($dbname) or die( "Unable to select database");

$sql_event_details = " SELECT * FROM Feed 
        LEFT JOIN User ON Feed.feedUserID = User.userID 
        LEFT JOIN ProfilePic ON ProfilePic.profilePicId = User.userProfilePicId
        LEFT JOIN Post ON Feed.feedPostID = Post.postID";	

//mysqli_set_charset($conn,"utf8");
$result=mysql_query($sql_event_details);
$num = mysql_num_rows($result);  

$i = $num-1;
while ( $i > -1) {{
  
//Profile Pic
$profilePicId =  mysql_result($result,$i,'profilePicId');
$profilePicCropUrl =  mysql_result($result,$i,'profilePicCropUrl');
$profilePicThumbUrl=  mysql_result($result,$i,'profilePicThumbUrl');
$profilePicUrl=  mysql_result($result,$i,'profilePicUrl');
            
//Cover pic         
$coverPicID=  mysql_result($result,$i,'coverPicID');
$coverPicCrop_url=  mysql_result($result,$i,'coverPicCropUrl');
$coverPicThumb_url=  mysql_result($result,$i,'coverPicThumbUrl');
$coverPicUrl=  mysql_result($result,$i,'coverPicUrl');

//Post
$postID =  mysql_result($result,$i,'postID');
$postMessage =  mysql_result($result,$i,'postMessage');
$postLink =  mysql_result($result,$i,'postLink');
$postMedia =  mysql_result($result,$i,'postMedia');

//Media
$mediaId = mysql_result($result,$i,'mediaId');
$mediaThumbUrl =  mysql_result($result,$i,'mediaThumbUrl');
$mediaUrl =  mysql_result($result,$i,'mediaUrl');

//Creator
$creatorArray = mysql_result($result,$i,'emailid');
$creatorFullName =  mysql_result($result,$i,'firstName');
$creatorDOB  =  mysql_result($result,$i,'dob');
$creatorContactNo  =  mysql_result($result,$i,'mobileNo');
$creatorProfilePicId  =  mysql_result($result,$i,'profilePicId');

//Feed
$feed = mysql_result($result,$i,'feedEntity');
$feedType = mysql_result($result,$i,'feedType');
$feedId = mysql_result($result,$i,'feedID');
$feedCommentCount = mysql_result($result,$i,'feedCommentCount');
$feedCreatedAt = mysql_result($result,$i,'feedCreatedAt');
$feedLikeCount = mysql_result($result,$i,'feedLikeCount');
$feedNormalPostID = mysql_result($result,$i,'feedPostID');
$feedCreatorID = mysql_result($result,$i,'feedCreatorID');
$feedEntityID = mysql_result($result,$i,'feedLevel');

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


            $json["Response"][] = [
                'feed' => [
                 'feedEntity' => $feed,
                 'feedType'=> $feedType,
                 'feedID' => $feedId,
                 'feedLevel' => $feedEntityID,
                 'feedCommentCount'=>$feedCommentCount,
                 'feedCreatedAt'=>$feedCreatedAt,
                 'feedLikeCount'=>$feedLikeCount,
                 'feedPostID'=>$feedNormalPostID,
                 'feedCreatorID'=>$feedCreatorID,
                 'creator'=>['creatorEmail' => $creatorArray,
                 'creatorFullName' => $creatorFullName, 
                 'creatorDOB'=>$creatorDOB,
                 'creatorContactNo'=>$creatorContactNo,
                 'creatorProfilePicId'=>$creatorProfilePicId,
                 'creatorFullName'=>$creatorFullName,
                 'profilePic'=>['profilePicId'=>$profilePicId,'profilePicUrl'=>$profilePicUrl]],
                 'Post'=>['postId'=>$postID,'postMessage'=> $postMessage, 'postLink'=>$postLink,
                 'Media'=>$json_media]
                ]
                ];

            }  
    $i--;
}
    
echo json_encode($json);

?>