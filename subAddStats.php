<?
require_once("db.php");

if (!isset($_SESSION["loginID"]) || $_SESSION["loginID"] == "") {
  ?><script>window.location = 'login.php';</script><?
}
?>
    
<style>
.floatright {
  position: relative;
  float: right;
  display: block;
  z-index: 999;
}

.setterbox {
  display: inline-block;
  width: 100px;
  height: 22px;
  /* float: right; */
  border: 1px red solid;
  margin-left: 25px;
}
</style>

<div class="col-md-12 ">
  <div class="content-top-1">
    <div class="col-md-12 top-content">
      <h5>Statistics!</h5> 
      SCOREBOARD - US: <span id="scoreus">0</span> &nbsp;THEM: <span id="scorethem">0</span>
      <div class="floatright"><button id="btncloseset" type="button" class="btn btn-warning">Close Set?</button></div>
        <div id="errormsg" class="alert alert-danger" style="display: none;"></div>
        <div class="form-inline">
          <select class="form-control" id="matchID" onchange="hideit();">
            <option value="0">Select a Match</option>
            <?
            $sql = "select * from matches order by matchdate desc";
            $result = mysqli_query($conn,$sql);
            while ($row = mysqli_fetch_array($result)) {
              ?>
              <option value="<?=$row["matchID"]; ?>"><?=$row["name"]; ?> (<?=$row["matchdate"]; ?>)</option>
              <?
            }
            ?>                
          </select>&nbsp;
          <select class="form-control" id="gamenumber" onchange="hideit();">
            <option value="0">Select a Set</option>
            <?
            for ($x=1;$x<6;$x++) {?>
            <option value="<?=$x; ?>"><?=$x; ?></option>
            <? } ?>
          </select><div id="floatingsetter" class="setterbox">SETTER: </div>
        </div>
        <table id="stattable">
          <thead>
          <tr >
            <th></th>
            <th>Action | Player # | Result</th>
            <th>&nbsp;</th>
          </tr>
          <tr>
            <td></td>
            <td><input type="text"  id="actionid" class="actionfield3"></td>
            <td>&nbsp;</td>
          </tr>

          </thead>
          <tbody>

          </tbody>
        </table>   
    </div>
  <div class="clearfix"> </div>
  
</div>


  <script>
  // INIT VARS
  var scoreus = 0;
  var scorethem = 0;
  var counter = 0;
  var ptus = 0;
  var ptthem = 0; 

  $(document).ready(function(){
      // INIT CURRENT SETTER / ENVIRONMENT 
      var currentsetter = 0;   
      $("#btncloseset").hide();

      // INIT ARRAYS
      var arrayActions =[
        <?
        $sql = "select * from shortcuts";
        $result = mysqli_query($conn,$sql);
        while ($row = mysqli_fetch_array($result)) { 
          ?>
          {"shortcut":"<?=$row["shortcut"]; ?>","action":"<?=$row["longdesc"]; ?>","number2":"<?=$row["number2"]; ?>","number3":"<?=$row["number3"]; ?>","point":"<?=$row["point"]; ?>","longdesc":"<?=$row["longdesc"]; ?>"  },
          <? } ?>
      ];
      
      var arrayPlayers =[
        <?
        if (isset($_SESSION["teamID"]) && $_SESSION["teamID"] != "") {
          $sql = "select * from players where teamID = '".$_SESSION["teamID"]."'";
        } else {
          $sql = "select * from players where userID = '".$_SESSION["loginID"]."'";
        }
        $result = mysqli_query($conn,$sql);
        while ($row = mysqli_fetch_array($result)) { 
          ?>
          {"shortcut":"<?=$row["number"]; ?>","player":"<?=$row["name"]; ?>","team":"us"},
          <? } ?>        
        ]

      var arrayResults =[];


      $("#matchID").focus();

      $('.actionfield3').keypress(function(e) {
        if ($("#matchID").val() != "0" && $("#gamenumber :selected").val() != "0") {
          $("#errormsg").hide();
          if (e.keyCode == 13) {// `0` works in mozilla and `32` in other browsers
            
             // GET TIME
             Date.prototype.getCurrentTime = function(){
                return ((this.getHours() < 10)?"0":"") + ((this.getHours()>12)?(this.getHours()-12):this.getHours()) +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds() + ((this.getHours()>12)?(' PM'):' AM');
                };

            var today = new Date(); //date object
            var current_date = today.getDate();
            var current_month = today.getMonth()+1; //Month starts from 0
            var current_year = today.getFullYear();
            var current_time = today.getCurrentTime();
            var displaytime = (current_month+"/"+current_date+"/"+current_year+' - '+current_time);
            // END TIME

            // CALCULATE THE ACTION FIRST
            var aryactionid = $("#actionid").val().trim();
            var aryActions = aryactionid.split(" ");
            var actionid = aryActions[0].toLowerCase();
            var playerid = aryActions[1];
            var num2  = aryActions[2];
            var num3  = aryActions[3];
            var nicedesc = "";

            if (jQuery.type( num2 ) === "undefined") {num2 = 0;}
            if (jQuery.type( num3 ) === "undefined") {num3 = 0;}

            var printresult = "";
            
            // TRANSLATE ACTION
            var printaction = $.map(arrayActions, function(value, key) {
              if (value.shortcut == actionid)
                 {
                    printresult =  value.longdesc;
                    if (actionid == "setter" || actionid == "set") {
                      currentsetter = playerid;
                      $("#floatingsetter").html("SETTER: "+currentsetter);
                    }
                    return value.action;
                 } 
            });
            //
            
            // TRANSLATE NUMBER1 - THE PLAYER 
            var printname = $.map(arrayPlayers, function(value, key) {
              if (value.shortcut == playerid)
                 {
                    var team = value.team;
                    return value.player;
                 } 
            });
            //

            // TRANSLATE POINT
            var getPoint = $.map(arrayActions, function(value, key) {
              if (value.shortcut == actionid)
                 {
                    // BASED ON TEAM
                    switch(value.point) {
                        case "yes":
                            $("#scoreus").html(++scoreus);++ptus;                            
                            break;
                        case "opp":
                            $("#scorethem").html(++scorethem);++ptthem;                            
                            break;
                    }

                    return scoreus + " | " +scorethem;
                 } 
            });

            // NOW DETERMINE IF THERE IS A SECOND NUMBER REQUIRED, AND ITS CLASSIFICATION
            var value2 = $.map(arrayActions, function(value, key) {
              if (value.shortcut == actionid)
                 {
                    // TYPE OF NUMBER
                    switch(value.number2) {
                        case "opp":
                            break;
                        case "player":
                            break;
                        case "no":
                            break;
                        case "rating":
                            printresult = printresult + "(" + num2 + " rating)";
                            if (actionid == 'p' && num2 == 0) {
                              $("#scorethem").html(++scorethem);++ptthem;
                              printresult = printresult +" - we were ACED ";
                            }
                            break;
                    }

                    return value.number2;
                 } 
            });
            // END DETERMINE NUMBER 2
            

            // NOW DETERMINE IF THERE IS A THIRD NUMBER REQUIRED, AND ITS CLASSIFICATION
            var value3 = $.map(arrayActions, function(value, key) {
              if (value.shortcut == actionid)
                 {
                    // TYPE OF NUMBER
                    switch(value.number3) {
                        case "opp":
                            break;
                        case "player":
                            break;
                        case "no":
                            break;
                        case "rating":
                            printresult = printresult + "(" + num3 + " rating)";
                            break;
                            
                    }

                    return value.number3;
                 } 
            });
            // END DETERMINE NUMBER 3

            // DEFAULTS
            if (value2 == "") {value2 = 'no';}
            if (value3 == "") {value3 = 'no';}

            if (scoreus < 0) {scoreus = 0;}
            if (scorethem < 0) {scorethem = 0;}

            // GET PLAYER 2 NAME
            if (value2 == "player") {
              var printname2 = $.map(arrayPlayers, function(value, key) {
                if (value.shortcut == num2)
                   {
                      return value.player;
                   } 
              });
              printname = printname + " and " + printname2;
            }
            // END PLAYER 2

            // GET PLAYER 3 NAME
            if (value3 == "player") {
              var printname3 = $.map(arrayPlayers, function(value, key) {
                if (value.shortcut == num3)
                   {
                      return value.player;
                   } 
              });
              printname = printname + " and " + printname3;
            }
            // END PLAYER 3

            ++counter;
            nicedesc = printname+" "+printresult+" at "+displaytime;
            //$("#stattable tbody").prepend("<tr id='row"+counter+"'><td></td><td colspan='4'>"+printname+" "+printresult+" at "+displaytime+"</td><td><button type='button' class='btn btn-danger' onclick='delrow(\"row"+counter+"\","+ptus+","+ptthem+");'>DELETE</button></td></tr>");
            $('input[type=text]').each(function(){
                $(this).val('');
            });

            // UPDATE SCORE AND ACTION IN DB
            var matchID = $("#matchID").val();
            var gamenumber = $("#gamenumber :selected").val();
            var val2 = value2.toString();
            var val3 = value3.toString();

            if (actionid == "opp") {
              num2 = playerid;
              playerid = "0";
            }
            $.ajax({
              method: "POST",
              url: "proc_addstats.php",
              data: { matchID: matchID, gamenumber: gamenumber, playernumber: playerid, value2:val2,value3:val3, number2: num2, number3: num3, playnumber: counter, actionid: actionid, pointus: ptus, pointthem: ptthem, nicedesc: nicedesc   }
            })
              .done(function( msg ) {
                //prompt("here", msg );
                // LOAD SET
                  hideit();
              });

            if (currentsetter != 0 && actionid == "k") {
              // RECORD ASSIST
              var setter = $.map(arrayPlayers, function(value, key) {
                if (value.shortcut == currentsetter)
                   {
                      return value.player;
                   } 
              });
              if (setter != playerid) {
              ++counter;
              nicedesc = setter+" gets the assist at "+displaytime;
              //$("#stattable tbody").prepend("<tr id='row"+counter+"'><td></td><td colspan='4'>"+setter+" gets the assist at "+displaytime+"</td><td><button type='button' class='btn btn-danger' onclick='delrow(\"row"+counter+"\","+ptus+","+ptthem+");'>DELETE</button></td></tr>");
              $('input[type=text]').each(function(){
                  $(this).val('');
              });

              $.ajax({
                method: "POST",
                url: "proc_addstats.php",
                data: { matchID: matchID, gamenumber: gamenumber, playernumber: currentsetter, value2: 'no', value3: 'no', number2: 0, number3: 0, playnumber: counter, actionid: "assist", pointus: 0, pointthem: 0, nicedesc: nicedesc   }
              })
                .done(function( msg ) {
                  // LOAD SET
                  hideit();
                });
              }
            }

            // ACES
            if (actionid == "s" && parseInt(num3) == 0) {
              ++counter;
              $("#scoreus").html(++scoreus);++ptus;
              nicedesc = printname+" gets an ACE on opponent "+num2+" at "+displaytime;
              //$("#stattable tbody").prepend("<tr id='row"+counter+"'><td></td><td colspan='4'>"+printname+" gets an ACE on opponent "+num2+" at "+displaytime+"</td><td><button type='button' class='btn btn-danger' onclick='delrow(\"row"+counter+"\","+ptus+","+ptthem+");'>DELETE</button></td></tr>");
              $('input[type=text]').each(function(){
                  $(this).val('');
              });

              $.ajax({
                method: "POST",
                url: "proc_addstats.php",
                data: { matchID: matchID, gamenumber: gamenumber, playernumber: playerid, value2: val2, value3: val3, number2: num2, number3: num3, playnumber: counter, actionid: "sa", pointus: 1, pointthem: 0, nicedesc: nicedesc   }
              })
                .done(function( msg ) {
                  // LOAD SET
                  hideit();
                });
            }
            // END DB CALL

            ptus = 0;
            ptthem = 0;
            
            $("#actionid").focus();
           }
         } else {
          if ($("#matchID").val() == "0") {
            $("#errormsg").show().html("Please select a match.");  
          } else {
            $("#errormsg").show().html("Please select a set.");  
          }
          
         }
      });

      $( "#btncloseset" ).click(function() {
        var matchID = $("#matchID").val();
        var gamenumber = $("#gamenumber :selected").val();
        alert(matchID+" "+gamenumber);
        $.ajax({
          method: "POST",
          url: "proc_closeset.php",
          data: { matchID: matchID, gamenumber: gamenumber}
        })
          .done(function( msg ) {
            //prompt("here", msg );
             $("#stattable tbody").load("subLoadSet.php?matchID="+$("#matchID").val()+"&gamenumber="+ $("#gamenumber :selected").val()); 
             $("#btncloseset").hide();

        });
      });
  });

  function delrow(rowtodelete,us,them) {
    $("#"+rowtodelete).remove();

    scoreus = scoreus - us;
    scorethem = scorethem - them;
    if (scoreus < 0) {scoreus = 0;}
    if (scorethem < 0) {scorethem = 0;}
    $("#scoreus").html(scoreus);
    $("#scorethem").html(scorethem);
    var matchID = $("#matchID").val();
    var gamenumber = $("#gamenumber :selected").val();
    
    $.ajax({
        method: "POST",
        url: "proc_killstats.php",
        data: { matchID: matchID, gamenumber: gamenumber, playnumber: rowtodelete}
      })
        .done(function( msg ) {
          hideit();
        });

  }

  function hideit() {
    $("#errormsg").hide();

    if ($("#matchID").val() != "0" && $("#gamenumber :selected").val() != "0") {
       $("#btncloseset").show();
       $("#stattable tbody").load("subLoadSet.php?matchID="+$("#matchID").val()+"&gamenumber="+ $("#gamenumber :selected").val()); 
       
       $.ajax({
              method: "POST",
              url: "proc_getscoreus.php",
              data: { matchID: $("#matchID").val(), gamenumber: $("#gamenumber :selected").val()}
            })
              .done(function( msg ) {
                $("#scoreus").html(msg);
                scoreus = parseInt(msg);
              });
        $.ajax({
              method: "POST",
              url: "proc_getscorethem.php",
              data: { matchID: $("#matchID").val(), gamenumber: $("#gamenumber :selected").val()}
            })
              .done(function( msg ) {
                $("#scorethem").html(msg);
                scorethem = parseInt(msg);
              });
        $.ajax({
              method: "POST",
              url: "proc_getcounter.php",
              data: { matchID: $("#matchID").val(), gamenumber: $("#gamenumber :selected").val()}
            })
              .done(function( msg ) {
                counter = parseInt(msg);
            });
       
       
      }
    }

    
      
  
  </script>