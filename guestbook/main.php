<?php
//session_start();   // session is started
$event_details = "";
?>

<html>
<head>
    <title>Events Search</title>
    <meta content="text/html">
</head>
<style>
    #tableTitle {font-style:italic; font-size:25px; text-align: center}
    #tableContent {font-size: 16px; line-height: 22px;}
    #search_table {background-color: rgb(245,245,245); border:2px solid rgb(220,220,220); width: 500px; padding: 5px;}
    #location_input {margin-left: 260px;}
    #search_btn {margin-left: 50px;}
    #show_results {
        border:1px solid lightgray;
        border-collapse: collapse;
        font-size:16px; width:80%;}
    #show_results td,th{border:1px solid lightgray;}
    #v_table th{text-align: right;border: 1px solid lightgray;}
    #v_table,#figure_table{
        border: 1px solid lightgray;
        border-collapse: collapse;
        width: 70%;}
    #v_table td{
        border: 1px solid lightgray;
        text-align: center;
        align-content: center}

    .grey_select {
        overflow-y: auto;
        border: none;
        text-align: center;
        font-size: 14px;
        background-color: lightgray;

    }

    option{
        text-align: center;
        height: 25px;
        width: 80px;
    }


    option:hover, option:checked{
        color: grey;
        background-color: darkgrey;
    }

    .hovergrey:hover {
        color: grey;
    }

    a, a:active{
        color: black;
        text-decoration: none;
    }

    a:hover {
        color: grey;
        text-decoration: none;

    }


</style>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkI-COCLg7jGjvVFFdF5On9dWPewHV5HQ"></script>
<script>

    function initMap(v_lat,v_lon,map_id){
        if(map_id !='x'){
            var elem = document.getElementsByClassName('maps');
            for (var i =0; i < elem.length; i++){
                if(elem[i].id != 'map'+map_id && elem[i].id != 'mode'+ map_id){
                    elem[i].style.display = 'none';
                }
            }

            if (document.getElementById('map' + map_id).style.display != 'block') {
                document.getElementById('map'+map_id).style.display ='block';
                document.getElementById('mode'+map_id).style.display ='block';
            } else {
                document.getElementById('map'+map_id).style.display ='none';
                document.getElementById('mode'+map_id).style.display ='none';

            }
        }

        var directionsService = new google.maps.DirectionsService();
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var v_location = {lat:parseFloat(v_lat),lng:parseFloat(v_lon)};
        var map_show = new google.maps.Map(document.getElementById('map'+map_id),{zoom: 10,center: v_location});
        var marker = new google.maps.Marker({position:v_location,map:map_show});

        directionsDisplay.setMap(map_show);
        document.getElementById('mode'+map_id).addEventListener('change', function() {
            calcRoute(v_lat,v_lon,directionsService, directionsDisplay,map_id);
        });
    }

    function calcRoute(v_lat,v_lon,directionsService,directionsDisplay,map_id) {
        var here = coords_json;

        var v_location = {lat:parseFloat(v_lat),lng:parseFloat(v_lon)};
        var selectedMode = document.getElementById('mode'+map_id).value;
        var request = {
            origin: here,
            destination: v_location,
            travelMode: google.maps.TravelMode[selectedMode]
        };
        directionsService.route(request, function (response, status)
        {
            if (status == 'OK') {
                directionsDisplay.setDirections(response);
            }
        });
    }

</script>
<script>
    function enableText() {
        if(document.getElementById("location_input").checked){
            document.getElementById("location_text").removeAttribute("disabled");
        }
        else{
            document.getElementById("location_text").setAttribute("disabled","");
        }
    }

    function clearForm(){
        document.getElementById("keyword").value = "";
        document.getElementById("here").checked = true;
        document.getElementById("category").selectedIndex = 0;
        document.getElementById("location_input").checked = false;
        document.getElementById("location_text").value = "";
        document.getElementById("location_text").disabled = true;
        document.getElementById("radius").value = "";
        if(document.getElementById("response_content").innerHTML){
            document.getElementById("response_content").innerHTML = "";
        }
    }


    function showTable(venue,v_address,v_city,v_state,v_postal,v_events,v_lat,v_lon) {
        venue = decodeURIComponent(venue).replace(/\+/g,' ');
        v_address = decodeURIComponent(v_address).replace(/\+/g,' ');
        v_city = decodeURIComponent(v_city).replace(/\+/g,' ');
        v_state = decodeURIComponent(v_state).replace(/\+/g,' ');
        v_postal =decodeURIComponent(v_postal).replace(/\+/g,' ');
        v_events = decodeURIComponent(v_events).replace(/\+/g,' ');

        if(venue || v_address || v_city || v_state || v_postal || v_events || v_lat || v_lon){
            var venue_table = "";
            venue_table += "<table id='v_table'>";
            venue_table += "<tr><th>Name</th><td>" + venue + "</td></tr>";
            venue_table += "<tr><th>Map</th><td>";
            if(v_lat && v_lon) {
                venue_table += "<div ><select class='grey_select' style='float:left' size='3' id ='modex'><option value='WALKING'>Walk there</option>" +
                    "<option value='BICYCLING'>Bike there</option><option value='DRIVING'>Drive there</option></select></div>" + "<div id='mapx' style='width: 400px;height:300px'></div>";
            }
            venue_table += "</td></tr>";
            venue_table += "<tr><th>Address</th><td>" + v_address + "</td></tr>";
            venue_table += "<tr><th>City</th>";
            if(v_city && v_state){
                venue_table += "<td>" + v_city + ", " + v_state + "</td>";
            } else {
                venue_table += "<td>" + v_city + v_state + "</td>";
            }
            venue_table += "</tr><tr><th>Postal Code</th><td>" + v_postal + "</td></tr>";
            venue_table += "<tr><th>Upcoming Events</th><td><a href=" + v_events  + ">"+ venue + " Tickets</td></tr>";
            venue_table += "</table>";

            document.getElementById("venue_table").innerHTML = venue_table;
            document.getElementById("table_arrowImage").src="http://csci571.com/hw/hw6/images/arrow_down.png";
            document.getElementById("change_table").innerHTML = "<p onclick=\"change_table_back('"+venue+"','"+v_address+"','"+v_city+"','"+v_state+"','"+v_postal+"','"+v_events+"','"+v_lat+"','"+v_lon+"')\">click to hide venue info</p>";
        } else {
            venue_table = "<h2>No Venue Info Found</h2>";
        }
        document.getElementById("venue_table").innerHTML = venue_table;
        document.getElementById("table_arrowImage").src="http://csci571.com/hw/hw6/images/arrow_down.png";
        document.getElementById("change_table").innerHTML = "<p onclick=\"change_table_back('"+venue+"','"+v_address+"','"+v_city+"','"+v_state+"','"+v_postal+"','"+v_events+"','"+v_lat+"','"+v_lon+"')\">click to hide venue info</p>";

    }


    function replace_html(name,date,artist_team,venue,genre,price_range,ticket_status,ticket_url,seat_map,v_address,v_city,v_state,v_postal,v_events,v_lat,v_lon,v_id) {
        latt = parseFloat(v_lat);
        long = parseFloat(v_lon);
        var newPage = "";
        newPage += "<div style='width:70%;margin:0 auto;'><div >";

        if(name && name!='N/A'){
            newPage += "<h1 align='center'>" + decodeURIComponent(name).replace(/\+/g,' ') + "</h1>";
        }

        if(seat_map){
            newPage += "<img style='float:right' src=" + seat_map + "><br>";
        }

        if(date && date!='N/A'){
            newPage += "<h2>Date</h2>" + date + "<br>";
        }
        if(artist_team){
            newPage += "<h2>Artist/Team</h2><div class='hovergrey'>" + decodeURIComponent(artist_team).replace(/\+/g,' ') + "</div><br>";
        }
        if(venue && venue!='N/A'){
            newPage += "<h2>Venue</h2>" + decodeURIComponent(venue).replace(/\+/g,' ') + "<br>";
        }
        if(genre && genre!='N/A'){
            newPage += "<h2>Genres</h2>" + decodeURIComponent(genre).replace(/\+/g,' ') + "<br>";
        }
        if(price_range){
            newPage += "<h2>Price Ranges</h2>" + price_range + "<br>";
        }
        if(ticket_status){
            newPage += "<h2>Ticket Status</h2>" + ticket_status + "<br>";
        }
        if(ticket_url){
            newPage += "<h2>Buy Ticket At:</h2><a class='hovergrey' href=" + ticket_url + ">Ticketmaster</a><br>";
        }
        if(v_lon && v_lat){
            newPage += "</div></div><div align='center'><div id='change_table'><p onclick=\"showTable('"+venue+"','"+v_address+"','"+v_city+"','"+v_state+"','"+v_postal+"','"+v_events+"','"+v_lat+"','"+v_lon+"');initMap('"+v_lat+"','"+v_lon+"','x')\">click to show venue info</p></div>";
        } else {
            newPage += "</div></div><div align='center'><div id='change_table'><p onclick=\"showTable('"+venue+"','"+v_address+"','"+v_city+"','"+v_state+"','"+v_postal+"','"+v_events+"','"+v_lat+"','"+v_lon+"')\">click to show venue info</p></div>";
        }
        newPage += "<img id='table_arrowImage' width = '40' src='http://csci571.com/hw/hw6/images/arrow_up.png'>"
        newPage += "<div id='venue_table'></div>"
        newPage += "<div id='change_image'><p onclick=\"showFigure('"+v_id+"')\">click to show venue photo</p></div>";
        newPage += "<img id='image_arrowImage' width = '40' src='http://csci571.com/hw/hw6/images/arrow_up.png'>"
        newPage += "<div id='venue_image'></div></div></div>"


        document.getElementById("response_content").innerHTML = newPage;

    }

    function change_table_back(venue,v_address,v_city,v_state,v_postal,v_events,v_lat,v_lon){
        document.getElementById("venue_table").innerHTML ="";
        if(v_lat && v_lon){
            document.getElementById("change_table").innerHTML = "<p onclick=\"showTable('"+venue+"','"+v_address+"','"+v_city+"','"+v_state+"','"+v_postal+"','"+v_events+"','"+v_lat+"','"+v_lon+"');initMap('"+v_lat+"','"+v_lon+"','x')\">click to show venue info</p>";
        } else {
            document.getElementById("change_table").innerHTML = "<p onclick=\"showTable('"+venue+"','"+v_address+"','"+v_city+"','"+v_state+"','"+v_postal+"','"+v_events+"','"+v_lat+"','"+v_lon+"')\">click to show venue info</p>";
        }
        document.getElementById("table_arrowImage").src = "http://csci571.com/hw/hw6/images/arrow_up.png";

    }

    function showFigure(v_id) {
        var venue_image ="";
        var v_image_arr = eval('v_image_json' + v_id);
        if (!v_image_arr.length) {
            venue_image = "<h2>No Venue Image Found</h2>";
        } else {
            venue_image = "<table id='figure_table'>";
            var v_image;
            for (var $id = 0; $id < v_image_arr.length; $id++){
                v_image = decodeURIComponent(v_image_arr[$id]).replace(/\+/g, ' ');
                venue_image += "<tr><td><img src='" + v_image + "'></td></tr>";
            }
            venue_image += "</table>";
        }


        document.getElementById("venue_image").innerHTML = venue_image;
        document.getElementById("image_arrowImage").src = "http://csci571.com/hw/hw6/images/arrow_down.png";
        document.getElementById("change_image").innerHTML = "<p onclick=\"change_image_back('" + v_id + "')\">click to hide image info</p>";


    }

    function change_image_back(v_id) {
        document.getElementById("venue_image").innerHTML ="";
        document.getElementById("change_image").innerHTML = "<p onclick=\"showFigure('" + v_id + "')\">click to show venue image</p>"
        document.getElementById("image_arrowImage").src = "http://csci571.com/hw/hw6/images/arrow_up.png";
    }

</script>





<?php
$keyword = $radius = $geoPoint = $geoPoint1 = $segmentId = "";
$details = json_decode(file_get_contents("http://ip-api.com/json"),true);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    $_SESSION["keyword"] = test_input($_POST["keyword"]);
    $_SESSION["radius"] = test_input($_POST["radius"]);
    $_SESSION["geoPoint"] = test_input($_POST["geoPoint"]);

    $_SESSION["geoPoint1"] = test_input($_POST["geoPoint1"]);

    $_SESSION["segmentId"] = $_POST["segmentId"];
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$default = "";
$music = "KZFzniwnSyZfZ7v7nJ";
$sports = "KZFzniwnSyZfZ7v7nE";
$arts = "KZFzniwnSyZfZ7v7na";
$film = "KZFzniwnSyZfZ7v7nn";
$miscellaneous = "KZFzniwnSyZfZ7v7n1" ;


if(empty($_POST["radius"])){
    $_SESSION["radius"] = "10";
}
$keyword = $_SESSION["keyword"];
$radius = $_SESSION["radius"];
$segmentId = $_SESSION["segmentId"];

?>



<body>
<table align="center" id="search_table">
    <tr><td id="tableTitle">Events Search<hr style='border-color=rgb(240,240,240);'></td></tr>
    <tr><td id="tableContent">
            <form method="POST" id ="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <b>Keyword </b><input type="text" name="keyword" id="keyword" value="<?php echo $_SESSION["keyword"];?>" required>
                <br>
                <b>Category </b><select name="segmentId" id="category" size="1">
                    <option selected id="default_cat" <?php if(isset($segmentId) && $segmentId==$default) echo "selected";?> value="">Default</option>
                    <option <?php if(isset($segmentId) && $segmentId==$music) echo "selected";?> value="KZFzniwnSyZfZ7v7nJ">Music</option>
                    <option <?php if(isset($segmentId) && $segmentId==$sports) echo "selected";?> value="KZFzniwnSyZfZ7v7nE">Sports</option>
                    <option <?php if(isset($segmentId) && $segmentId==$arts) echo "selected";?> value="KZFzniwnSyZfZ7v7na">Arts & Theatre</option>
                    <option <?php if(isset($segmentId) && $segmentId==$film) echo "selected";?> value="KZFzniwnSyZfZ7v7nn">Film</option>
                    <option <?php if(isset($segmentId) && $segmentId==$miscellaneous) echo "selected";?> value="KZFzniwnSyZfZ7v7n1">Miscellaneous</option>
                </select>

                <br>
                <b>Distance(miles) </b>
                <input type="text" name="radius" id="radius" placeholder="10" value="<?php echo $_SESSION["radius"]; ?>">
                <input type="hidden" name="unit" value="miles">
                <b>from </b>
                <input type="radio" name="geoPoint" value="Here" id="here" checked <?php if(isset($_SESSION['geoPoint']) && $_SESSION['geoPoint'] == "Here")  echo ' checked';?> onclick="enableText()">Here
                <br>

                <input type="radio" name="geoPoint" value="other" id="location_input" <?php if(isset($_SESSION['geoPoint']) && ($_SESSION['geoPoint']== "other"))  echo ' checked="checked"';?> onclick="enableText()">

                <input type="text" name="geoPoint1" id="location_text" placeholder="location" value="<?php echo $_SESSION['geoPoint1']; ?>" disabled required>
                <br>
                <input id="search_btn" type="submit" name="submit" value="search" <?php if (!$details){ ?> disabled <?php } ?>>
            </form>
            <form method="post" name="form2">
                <input type="submit" name="reset" value="clear" style="float: left; margin-left: 120px; margin-top: -34px;" onclick="clearForm()" <?php if(isset($_POST['reset'])) {$_SESSION=array();}?> >
            </form>
        </td></tr>
</table>



<?php
if(isset($_POST["submit"])){
    $keyword = $_SESSION["keyword"];
    $radius = $_SESSION["radius"];
    $segmentId = $_SESSION["segmentId"];
    $geoPoint = $_SESSION["geoPoint"];
    $geoPoint1 = $_SESSION["geoPoint1"];

    //$tm_key ='3v0iAigs1N0RgPtT1khHXnIw7bbzZGgX';
    $tm_key = 'FmO5yNSuaOge09QMwMAcdm9aCs3LIalP';

    $map_key = 'AIzaSyBkI-COCLg7jGjvVFFdF5On9dWPewHV5HQ';

    echo "<div id='response_content'>";
    $lat = "";
    $lon = "";
    if(isset($_POST['geoPoint']) && $geoPoint == 'Here'){
        $lat = $details['lat'];
        $lon = $details['lon'];
        include 'geoHash.php';
        $geoPoint_get = encode($lat, $lon);
    } else if(isset($_POST['geoPoint']) && $geoPoint == 'other'){
        $geoPoint1 = str_replace(" ","%2B",$geoPoint1);
        $geo_url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$geoPoint1."&key=".$map_key;
        $geo_url;
        $geo_file = file_get_contents($geo_url);
        $geo_json = json_decode($geo_file,true);
        $lat =$geo_json['results']['0']['geometry']['location']['lat'];
        $lon =$geo_json['results']['0']['geometry']['location']['lng'];
        include 'geoHash.php';
        $geoPoint_get = encode($lat,$lon);
    }

    $coords_obj=array('lat' => $lat, 'lng' => $lon);
    $coords_json =json_encode($coords_obj);

    echo "<script>var coords_json=".$coords_json.";</script>";



    $tm_url = "https://app.ticketmaster.com/discovery/v2/events.json?"."apikey=".$tm_key."&keyword=".$keyword."&radius=".$radius."&unit=miles"."&geoPoint=".$geoPoint_get."&segmentId=".$segmentId;
    sleep(1);
    //echo $tm_url;
    $tm_url = str_replace(" ", "%20", $tm_url);


    $tm_json = file_get_contents($tm_url,true); // put the contents of the file into a variable
    //echo $tm_json;
    $tm_result = json_decode($tm_json,true); // decode the JSON feed
    if(sizeof($tm_result) < 3 || array_keys($tm_result)[0] == 'errors' ){
        echo "<p align='center' style='margin-left:250px;margin-right:250px;background-color:rgb(220,220,220); border: 1px solid grey;padding: 1px;font-size:14px;'>No Record has been found</p>";
        $tm_result='';
    } else{

        echo "<table align='center' id='show_results'><tr><b><th>Date</th><th>Icon</th><th>Event</th><th>Genre</th><th>Venue</th></b></tr>";
        $events = $tm_result['_embedded']['events'];
        $counter = 0;
        foreach ($events as $event){
            $event_url = "https://app.ticketmaster.com/discovery/v2/events/".$event['id']."?apikey=".$tm_key;
            sleep(1);
            $event_json = json_decode(file_get_contents($event_url),true);

            $name = "";
            if(array_key_exists('name',$event_json) && $event_json['name'] && $event_json['name'] != "Undefined"){
                $name = urlencode($event_json['name']);
            }

            $date = "";
            if(array_key_exists('dates',$event_json) && array_key_exists('start',$event_json['dates']) && array_key_exists('localDate',$event_json['dates']['start'])){
                if($event_json['dates']['start']['localDate'] && $event_json['dates']['start']['localDate'] != "Undefined"){
                    $date .= $event_json['dates']['start']['localDate'];
                    $date_1 = $event_json['dates']['start']['localDate'];
                }
            }
            if(array_key_exists('dates',$event_json) && array_key_exists('start',$event_json['dates']) && array_key_exists('localTime',$event_json['dates']['start'])){
                if($event_json['dates']['start']['localDate'] && $event_json['dates']['start']['localDate'] != "Undefined"){
                    $date .= ' '.$event_json['dates']['start']['localTime'];
                    $date_2 = $event_json['dates']['start']['localTime'];
                }
            }

            $artist_team_print = '';
            if(array_key_exists('_embedded',$event_json) && array_key_exists('attractions',$event_json['_embedded'])){
                $artist_teams = $event_json['_embedded']['attractions'];
                for ($i = 0;$i < count($artist_teams);$i++){
                    if(array_key_exists('url',$artist_teams[$i])){
                        $artist_team_print .= "<a href=".$artist_teams[$i]['url'];
                        $artist_team_print .= ">".urlencode($artist_teams[$i]['name']);
                        $artist_team_print .= "</a>";
                    }else{
                        $artist_team_print .= urlencode($artist_teams[$i]['name']);
                    }
                    if($i < count($artist_teams) - 1){
                        $artist_team_print .= " | ";
                    }
                }


            }

            $genre = "";
            $genre_table = "N/A";
            if(array_key_exists('classifications',$event_json)){
                $class = $event_json['classifications'][0];
                if(array_key_exists('segment',$class) && $class['segment']['name'] != "Undefined"){
                    if($genre == ""){
                        $genre .= $class['segment']['name'];
                        $genre_table =  $class['segment']['name'];
                    } else{
                        $genre .= " | ".$class['segment']['name'];
                    }
                }
                if(array_key_exists('type',$class) && $class['type']['name'] != "Undefined"){
                    if($genre == ""){
                        $genre .= $class['type']['name'];
                    } else{
                        $genre .= " | ".$class['type']['name'];
                    }
                }
                if(array_key_exists('subType',$class) && $class['subType']['name'] != "Undefined"){
                    if($genre == ""){
                        $genre .= $class['subType']['name'];
                    } else{
                        $genre .= " | ".$class['subType']['name'];
                    }
                }
                if(array_key_exists('Genre',$class) &&  $class['Genre']['name'] != "Undefined"){
                    if($genre == ""){
                        $genre .= $class['genre']['name'];
                    } else{
                        $genre .= " | ".$class['genre']['name'];
                    }
                }
                if(array_key_exists('subGenre',$class) && $class['subGenre']['name'] != "Undefined"){
                    if($genre == ""){
                        $genre .= $class['subGenre']['name'];
                    } else{
                        $genre .= " | ".$class['subGenre']['name'];
                    }
                }
                if($genre==""){
                    $genre = "N/A";
                }
                $genre = urlencode($genre);

            }

            $price_ranges = "";
            if(array_key_exists('priceRanges',$event_json)){
                $price = $event_json['priceRanges'][0];
                if(array_key_exists('min',$price) && array_key_exists('max',$price)){
                    $price_ranges .= $event_json['priceRanges'][0]['min']."-";
                    $price_ranges .= $event_json['priceRanges'][0]['max']." ";
                    $price_ranges .= $event_json['priceRanges'][0]['currency'];
                } elseif(array_key_exists('min',$price)){
                    $price_ranges .= $event_json['priceRanges'][0]['min']." ";
                    $price_ranges .= $event_json['priceRanges'][0]['currency'];

                } elseif(array_key_exists('max',$price)){
                    $price_ranges .= $event_json['priceRanges'][0]['max']." ";
                    $price_ranges .= $event_json['priceRanges'][0]['currency'];
                }
            }

            $ticket_status = "";
            if(array_key_exists('dates',$event_json) && array_key_exists('status',$event_json['dates']) && array_key_exists('code',$event_json['dates']['status'])){
                $ticket_status = $event_json['dates']['status']['code'];
            }

            $ticket_url = "";
            if(array_key_exists('url',$event_json)){
                $ticket_url = $event_json['url'];
            }

            $seatmap_url = '';
            if(array_key_exists('seatmap',$event_json) && array_key_exists('staticUrl',$event_json['seatmap'])){
                $seatmap_url = $event_json['seatmap']['staticUrl'];
            }

            $event_lat = "";
            $event_lon = "";
            $venue = "";
            if(array_key_exists('_embedded',$event_json) && array_key_exists('name',$event_json['_embedded']['venues'][0])){
                $venue_1 = $event_json['_embedded']['venues'][0];
                $venue = $venue_1['name'];
                if(array_key_exists('location',$venue_1) && array_key_exists('latitude',$venue_1['location']) && array_key_exists('longitude',$venue_1['location'])){
                    $event_lat = $venue_1['location']['latitude'];
                    $event_lon = $venue_1['location']['longitude'];
                }
            } else {
                $venue="N/A";
            }
            $venue = urlencode($venue);


            $venue_address = "";
            $venue_city = "";
            $venue_state = "";
            $venue_postal = "";
            $venue_images = array();
            $venue_events = "";

            if($venue != "N/A"){
                $venue_url = "https://app.ticketmaster.com/discovery/v2/venues/?apikey=".$tm_key."&keyword=".$venue;
                sleep(1);
                $venue_json =  json_decode(file_get_contents($venue_url),true);
                if(array_key_exists('_embedded',$venue_json) && array_key_exists('venues',$venue_json['_embedded'])){
                    $venue_record = $venue_json['_embedded']['venues'][0];
                    if(array_key_exists('address',$venue_record) && array_key_exists('line1',$venue_record['address'])){
                        $venue_address = $venue_record['address']['line1'];
                    }
                    if(array_key_exists('city',$venue_record) && array_key_exists('name',$venue_record['city'])){
                        $venue_city = $venue_record['city']['name'];
                    }
                    if(array_key_exists('state',$venue_record) && array_key_exists('stateCode',$venue_record['state'])){
                        $venue_state = $venue_record['state']['stateCode'];
                    }
                    if(array_key_exists('postalCode',$venue_record)){
                        $venue_postal = $venue_record['postalCode'];
                    }
                    if(array_key_exists('url',$venue_record)){
                        $venue_events = $venue_record['url'];
                    }
                    if(array_key_exists('images',$venue_record)){
                        $venue_images = $venue_record['images'];
                    }
                }
            }


            $v_image = array();
            for($j = 0;$j < count($venue_images); $j++){
                if(array_key_exists('url',$venue_images[$j]) && $venue_images[$j]['url'] != "Undefined" ){
                    $venue_image = $venue_images[$j]['url'];
                    array_push($v_image,urlencode($venue_image));
                }
            }
            $v_image_json = json_encode($v_image);
            echo "<script>var v_image_json".$counter."=".$v_image_json.";</script>";


            $icon = "";
            if(array_key_exists('images',$event)){
                if(array_key_exists('url',$event['images'][0])){
                    $icon = $event['images'][0]['url'];
                }
            }


            echo "<tr><td align='center'>";
            echo $date_1.'<br>'.$date_2;
            echo "</td><td width='120' align='center'>";
            if($icon){
                echo "<img width='80' src=".$icon.">";
            }
            echo "</td><td>";
            echo "<p class='hovergrey' onclick=\"replace_html("."'".$name."','".$date."','".$artist_team_print."','".$venue."','".$genre.
                "','".$price_ranges."','".$ticket_status."','".$ticket_url."','".$seatmap_url."','".$venue_address."','"
                .$venue_city."','".$venue_state."','".$venue_postal."','".$venue_events."','".$event_lat."','".$event_lon."','".$counter.
                "')\">".$event['name']."</p>";
            echo "</td><td>";
            echo $genre_table;
            echo "</td><td style='position:relative;'>";

            $event_name = 'N/A';


            if($event_lon && $event_lat){
                echo "<div class='hovergrey' onclick=\"initMap(".$event_lat.","."$event_lon".",".$counter.")\">".urldecode($venue)."</div>";
                echo "<div ><select class='maps' style='overflow-y:auto; border:none; text-align:center; font-size:14px; background-color:lightgray;display:none;position:absolute;z-index: 4;top: 50px;left:30px;' size='3' id ='mode".$counter."'><option value='WALKING'>Walk there</option>".
                    "<option value='BICYCLING'>Bike there</option><option value='DRIVING'>Drive there</option></select> </div>";
                echo "<div class='maps' style='display:none;position: absolute;z-index: 3;top:50px;left:30px;width:350px;height:300px; ' id='map".$counter."'></div>";
            } else {
                echo urldecode($venue);
            }


            echo "</td></tr>";
            $counter ++;
        }
    }
}
echo "</div>";




?>





</body>
</html>

