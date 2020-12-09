@extends('layouts.default')
@section('content')
<style>
.borderedTable {
	padding: 5px;
	height: 350px;
    overflow: auto;
	word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 5px;
    -webkit-box-shadow: 0 0 5px 0 rgba(43,43,43,.1), 0 11px 6px -7px rgba(43,43,43,.1);
    box-shadow: 0 0 5px 0 rgba(43,43,43,.1), 0 11px 6px -7px rgba(43,43,43,.1);
    margin-bottom: 30px;
    -webkit-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
}

.table-condensed{
  font-size: 12px;
}
 
 .kwBigNumber {
	font-size: 28px;
	color: black;
	/*font-weight: bold;*/
}
.kwLittleNumber {
	font-size: 16px;
	color: black;	
	/*font-weight: bold;*/
}
.tdpadding {
	padding: 0px 25px;
	/*line-height: 99%;*/
	line-height: 18px;
}
.ui-widget-header {
	background: #fff;
}

	/*  NEW TABLE STRUCTURE */
@font-face{font-family:Lato-Regular;src:url(../fonts/Lato/Lato-Regular.ttf)}@font-face{font-family:Lato-Bold;src:url(../fonts/Lato/Lato-Bold.ttf)}*{margin:0;padding:0;box-sizing:border-box}body,html{height:100%;font-family:sans-serif}a{margin:0;transition:all .4s;-webkit-transition:all .4s;-o-transition:all .4s;-moz-transition:all .4s}a:focus{outline:none!important}a:hover{text-decoration:none}h1,h2,h3,h4,h5,h6{margin:0}p{margin:0}ul,li{margin:0;list-style-type:none}input{display:block;outline:none;border:none!important}textarea{display:block;outline:none}textarea:focus,input:focus{border-color:transparent!important}button{outline:none!important;border:none;background:0 0}button:hover{cursor:pointer}iframe{border:none!important}.js-pscroll{position:relative;overflow:hidden}.table100 .ps__rail-y{width:9px;background-color:transparent;opacity:1!important;right:5px}.table100 .ps__rail-y::before{content:"";display:block;position:absolute;background-color:#ebebeb;border-radius:5px;width:100%;height:calc(100% - 30px);left:0;top:15px}.table100 .ps__rail-y .ps__thumb-y{width:100%;right:0;background-color:transparent;opacity:1!important}.table100 .ps__rail-y .ps__thumb-y::before{content:"";display:block;position:absolute;background-color:#ccc;border-radius:5px;width:100%;height:calc(100% - 30px);left:0;top:15px}.limiter{width:1366px;margin:0 auto}.container-table100{width:100%;min-height:100vh;background:#fff;display:-webkit-box;display:-webkit-flex;display:-moz-box;display:-ms-flexbox;display:flex;align-items:center;justify-content:center;flex-wrap:wrap;padding:33px 30px}.wrap-table100{width:1170px}.table100{background-color:#fff}table{width:100%}th,td{font-weight:unset;padding-right:10px}.column1{width:33%;padding-left:40px}.column2{width:13%}.column3{width:22%}.column4{width:19%}.column5{width:13%}.table100-head th{padding-top:18px;padding-bottom:18px}.table100-body td{padding-top:16px;padding-bottom:16px}.table100{position:relative;padding-top:60px}.table100-head{position:absolute;width:100%;top:0;left:0}.table100-body{max-height:585px;overflow:auto}.table100.ver1 th{font-family:Lato-Bold;font-size:18px;color:#fff;line-height:1.4;background-color:#6c7ae0}.table100.ver1 td{font-family:Lato-Regular;font-size:15px;color:gray;line-height:1.4}.table100.ver1 .table100-body tr:nth-child(even){background-color:#f8f6ff}.table100.ver1{border-radius:10px;overflow:hidden;box-shadow:0 0 40px 0 rgba(0,0,0,.15);-moz-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-webkit-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-o-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-ms-box-shadow:0 0 40px 0 rgba(0,0,0,.15)}.table100.ver1 .ps__rail-y{right:5px}.table100.ver1 .ps__rail-y::before{background-color:#ebebeb}.table100.ver1 .ps__rail-y .ps__thumb-y::before{background-color:#ccc}.table100.ver2 .table100-head{box-shadow:0 5px 20px 0 rgba(0,0,0,.1);-moz-box-shadow:0 5px 20px 0 rgba(0,0,0,.1);-webkit-box-shadow:0 5px 20px 0 rgba(0,0,0,.1);-o-box-shadow:0 5px 20px 0 rgba(0,0,0,.1);-ms-box-shadow:0 5px 20px 0 rgba(0,0,0,.1)}.table100.ver2 th{font-family:Lato-Bold;font-size:18px;color:#fa4251;line-height:1.4;background-color:transparent}.table100.ver2 td{font-family:Lato-Regular;font-size:15px;color:gray;line-height:1.4}.table100.ver2 .table100-body tr{border-bottom:1px solid #f2f2f2}.table100.ver2{border-radius:10px;overflow:hidden;box-shadow:0 0 40px 0 rgba(0,0,0,.15);-moz-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-webkit-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-o-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-ms-box-shadow:0 0 40px 0 rgba(0,0,0,.15)}.table100.ver2 .ps__rail-y{right:5px}.table100.ver2 .ps__rail-y::before{background-color:#ebebeb}.table100.ver2 .ps__rail-y .ps__thumb-y::before{background-color:#ccc}.table100.ver3{background-color:#393939}.table100.ver3 th{font-family:Lato-Bold;font-size:15px;color:#00ad5f;line-height:1.4;text-transform:uppercase;background-color:#393939}.table100.ver3 td{font-family:Lato-Regular;font-size:15px;color:gray;line-height:1.4;background-color:#222}.table100.ver3{border-radius:10px;overflow:hidden;box-shadow:0 0 40px 0 rgba(0,0,0,.15);-moz-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-webkit-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-o-box-shadow:0 0 40px 0 rgba(0,0,0,.15);-ms-box-shadow:0 0 40px 0 rgba(0,0,0,.15)}.table100.ver3 .ps__rail-y{right:5px}.table100.ver3 .ps__rail-y::before{background-color:#4e4e4e}.table100.ver3 .ps__rail-y .ps__thumb-y::before{background-color:#00ad5f}.table100.ver4{margin-right:-20px}.table100.ver4 .table100-head{padding-right:20px}.table100.ver4 th{font-family:Lato-Bold;font-size:18px;color:#4272d7;line-height:1.4;background-color:transparent;border-bottom:2px solid #f2f2f2}.table100.ver4 .column1{padding-left:7px}.table100.ver4 td{font-family:Lato-Regular;font-size:15px;color:gray;line-height:1.4}.table100.ver4 .table100-body tr{border-bottom:1px solid #f2f2f2}.table100.ver4{overflow:hidden}.table100.ver4 .table100-body{padding-right:20px}.table100.ver4 .ps__rail-y{right:0}.table100.ver4 .ps__rail-y::before{background-color:#ebebeb}.table100.ver4 .ps__rail-y .ps__thumb-y::before{background-color:#ccc}.table100.ver5{margin-right:-30px}.table100.ver5 .table100-head{padding-right:30px}.table100.ver5 th{font-family:Lato-Bold;font-size:14px;color:#555;line-height:1.4;text-transform:uppercase;background-color:transparent}.table100.ver5 td{font-family:Lato-Regular;font-size:15px;color:gray;line-height:1.4;background-color:#f7f7f7}.table100.ver5 .table100-body tr{overflow:hidden;border-bottom:10px solid #fff;border-radius:10px}.table100.ver5 .table100-body table{border-collapse:separate;border-spacing:0 10px}.table100.ver5 .table100-body td{border:solid 1px transparent;border-style:solid none;padding-top:10px;padding-bottom:10px}.table100.ver5 .table100-body td:first-child{border-left-style:solid;border-top-left-radius:10px;border-bottom-left-radius:10px}.table100.ver5 .table100-body td:last-child{border-right-style:solid;border-bottom-right-radius:10px;border-top-right-radius:10px}.table100.ver5 tr:hover td{background-color:#ebebeb;cursor:pointer}.table100.ver5 .table100-head th{padding-top:25px;padding-bottom:25px}.table100.ver5{overflow:hidden}.table100.ver5 .table100-body{padding-right:30px}.table100.ver5 .ps__rail-y{right:0}.table100.ver5 .ps__rail-y::before{background-color:#ebebeb}.table100.ver5 .ps__rail-y .ps__thumb-y::before{background-color:#ccc}
</style>
<?
if (!empty(Session::get("start_date"))) { $start = Session::get("start_date"); } else { $start = date("Y-m-d",strtotime("-1 days")); }
if (!empty(Session::get("end_date"))) { $end = Session::get("end_date"); } else { $end = date("Y-m-d");; }
?>
  <div class="container-fluid appwindow clearfix">
	<div class="form-group" style="float:right;position: relative;top: 30px;"><button id="btnSearchRevByTA" style="float:right;height: 20px;font-size: 10px;" onclick="runSearchTrafficByAccount();">GO</button><input type="text" id="enddate" placeholder="End Date" class="form-control input-sm" style="width:33%;float: right;margin-right:3px;" value="<?=$end; ?>"><input type="text" id="startdate" placeholder="Start Date" class="form-control input-sm" style="width:33%;float: right;margin-right:3px;" value="<?=$start; ?>"></div>
    <input type="hidden" name="myToken" id="myToken" value="{{ csrf_token() }}">
    <h2><i class="fa fa-tachometer" aria-hidden="true"></i>&nbsp; Dashboard</h2><span id="alerttext" style="color: red;font-weight: bold"></span>
	
    <div class="row">
		
		<div class="col-xs-12 col-md-6 ">
			<div id="chartRevenue" class="borderedTable"><img src="/img/ajax-loader.gif"></div>
		</div>
		<div class="col-md-6">
			<!--<p>Revenue by Traffic Account</p>-->
			<div id="chartRevenueByTrafficAccount" class="borderedTable"><img src="/img/ajax-loader.gif"></div>

		</div>
		
	</div>

	<div class="row">
		<div class="col-md-6">
			<!--<p>Today vs Yesterday ROAS by site</p>-->
			<div id="chartROASTodayvsYesterday" class="borderedTable"><img src="/img/ajax-loader.gif"></div>

		</div>
		<div class="col-xs-12 col-md-6">
			<!--Top 10 scrub rate for yesterday-->
			<div id="scrubRate" class="borderedTable"><img src="/img/ajax-loader.gif"></div>

		</div>
	</div>
		
    <div class="row">
		
		<div class="col-xs-12 col-md-12">
			<!--TQ or Profit-->
			<div id="tqprofit" class="borderedTable" style="height:500px;"><img src="/img/ajax-loader.gif"></div>

		</div>
	</div>

    <div class="row">
		
		
		<div class="col-md-6">
			<div id="breakdown" class="borderedTable"><img src="/img/ajax-loader.gif"></div>

		</div>
		<div class="col-md-6">
			<div id="tq5day" class="borderedTable"><img src="/img/ajax-loader.gif"></div>

		</div>
	</div>

	  <div class="row">


		  <div class="col-md-6">
			  <div id="hourlydata" class="borderedTable"><img src="/img/ajax-loader.gif"></div>

		  </div>
		  <div class="col-md-6">
			  <div id="hourlykw" class="borderedTable"><img src="/img/ajax-loader.gif"></div>

		  </div>
	  </div>

  </div>
  <!-- Modal -->
<div class="modal fade" id="scrubByRG" tabindex="-1" role="dialog" aria-labelledby="scrubByRGLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body" id="modalload">
        <img src="/img/ajax-loader.gif">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <!-- Modal -->
<div class="modal fade" id="kwmodalpanel" tabindex="-1" role="dialog" aria-labelledby="kwmodalpanelLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width:90%; overflow-x: hidden;">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <div class="modal-content">
      <div class="modal-body" id="kwmodalload">
        <img src="/img/ajax-loader.gif">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <div class="clearfix"></div>
  <script type="text/javascript" src="js/{{ $adserverjsname }}"></script>
  <script type="text/javascript" src="js/Chart.bundle.min.js"></script>
  <script>
    $( document ).ready(function() {
		var summaryStartDate = $("#startdate").val();
		var summaryEndDate = $("#enddate").val();
		
		
		$("#chartRevenue").load("/dashboardRevenue");

		$("#hourlydata").load("/dashboardHourly", function() {
			var groupColumn = 3;
			var table = $('#hourlyTable').DataTable({
				dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr  >>" +
						"<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
						"<'row'<'col-sm-12'> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
				buttons: [
					{
						extend:    'copyHtml5',
						text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
						title:	   'ASUI Hourly Data'

					},
					{
						extend:    'csvHtml5',
						text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
						title:	   'ASUI Hourly Data'
					},
					{
						extend:    'excel',
						text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
						title:	   'ASUI Hourly Data'
					},
					{
						extend:    'pdf',
						text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
						orientation: 'landscape',
						title:	   'ASUI Hourly Data'
					},
					{
						extend: 	'print',
						text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
						autoPrint: 	false,
						title:	   	'ASUI Hourly Data'
					}


				],
				"columnDefs": [
					{
						render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
						"targets": [1,2,3,4]
					}
				],
				"order": [[ groupColumn, 'desc' ]],
				"displayLength": 50,
				"drawCallback": function ( settings ) {
					var api = this.api();
					var rows = api.rows( {page:'current'} ).nodes();
					var last=null;
				}
			} );



		});

		$("#hourlykw").load("/dashboardHourlyKW", function() {
			var groupColumn = 3;
			var table = $('#hourlyKWTable').DataTable({
				dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr  >>" +
						"<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
						"<'row'<'col-sm-12'> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
				buttons: [
					{
						extend:    'copyHtml5',
						text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
						title:	   'ASUI Hourly Data'

					},
					{
						extend:    'csvHtml5',
						text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
						title:	   'ASUI Hourly Data'
					},
					{
						extend:    'excel',
						text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
						title:	   'ASUI Hourly Data'
					},
					{
						extend:    'pdf',
						text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
						orientation: 'landscape',
						title:	   'ASUI Hourly Data'
					},
					{
						extend: 	'print',
						text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
						autoPrint: 	false,
						title:	   	'ASUI Hourly Data'
					}


				],
				"columnDefs": [
					{
						render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
						"targets": [1,2,3,4]
					}
				],
				"order": [[ groupColumn, 'desc' ]],
				"displayLength": 50,
				"drawCallback": function ( settings ) {
					var api = this.api();
					var rows = api.rows( {page:'current'} ).nodes();
					var last=null;
				}
			} );



		});
		
		$("#chartRevenueByTrafficAccount").load("/dashboardRevenueByTrafficAccount", function() {
			$( "#startdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "#enddate" ).datepicker({ dateFormat: 'yy-mm-dd' });	
			if ($.fn.dataTable.isDataTable('#rbtaTable')) {
				var dtable = $('#rbtaTable').DataTable();
				dtable.destroy();
			}
			var groupColumn = 0;
			var table = $('#rbtaTable').DataTable({
				dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr  >>" +
            "<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
            "<'row'<'col-sm-12'> >" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				buttons: [
                {
                    extend:    'copyHtml5',
                    text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
                    title:	   'ASUI Revenue by Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
					
                },
                {
                    extend:    'csvHtml5',
                    text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
                    title:	   'ASUI Revenue by Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
					extend:    'excel',
					text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
					title:	   'ASUI Revenue by Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend:    'pdf',
                    text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
					orientation: 'landscape',
                	title:	   'ASUI Revenue by Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend: 	'print',
                    text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
                    autoPrint: 	false,
					title:	   	'ASUI Revenue by Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                }
				
    
            ],
				"columnDefs": [
					{ "visible": false, "targets": groupColumn }
				],
				"order": [[ groupColumn, 'asc' ]],
				"displayLength": 50,
				"drawCallback": function ( settings ) {
					var api = this.api();
					var rows = api.rows( {page:'current'} ).nodes();
					var last=null;
		 
					api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
						if ( last !== group ) {
							$(rows).eq( i ).before(
								'<tr class="group bg-success"><td colspan="5">'+group+'</td></tr>'
							);
		 
							last = group;
						}
					} );
				}
			} );
			
			// Order by the grouping
			$('#rbtaTable tbody').on( 'click', 'tr.group', function () {
				var currentOrder = table.order()[0];
				if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
					table.order( [ groupColumn, 'desc' ] ).draw();
				}
				else {
					table.order( [ groupColumn, 'asc' ] ).draw();
				}
			} );
			
		});	
		$("#chartROASTodayvsYesterday").load("/dashboardROAS");
		$("#scrubRate").load("/dashboardScrub", function() {
			var groupColumn = 3;
			var tableScrub = $('#tableScrub').DataTable({
				dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr  >>" +
            "<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
            "<'row'<'col-sm-12'> >" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				buttons: [
                {
                    extend:    'copyHtml5',
                    text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
                    title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
					
                },
                {
                    extend:    'csvHtml5',
                    text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
                    title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
					extend:    'excel',
					text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
					title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend:    'pdf',
                    text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
					orientation: 'landscape',
                	title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend: 	'print',
                    text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
                    autoPrint: 	false,
					title:	   	'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                }
				
    
            ],
				"order": [[ groupColumn, 'asc' ]],
				"displayLength": 50				
			});
		});
		$("#tqprofit").load("/dashboardTQProfit", function() {
			var groupColumn = 8;
			var tableTQProfit = $('#tableTQProfit').DataTable({
				dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr  >>" +
            "<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
            "<'row'<'col-sm-12'> >" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				buttons: [
                {
                    extend:    'copyHtml5',
                    text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
                    title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
					
                },
                {
                    extend:    'csvHtml5',
                    text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
                    title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
					extend:    'excel',
					text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
					title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend:    'pdf',
                    text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
					orientation: 'landscape',
                	title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend: 	'print',
                    text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
                    autoPrint: 	false,
					title:	   	'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                }
				
    
            ],
				"order": [[ groupColumn, 'desc' ]],
				"displayLength": 50	,
				"columnDefs": [
					{						
						render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
						"targets": [6,7,8,9,10]
					},
					{ "searchable": true, "targets": [0,1] }
				],
				
				
				 "footerCallback": function ( row, data, start, end, display ) {
					var api = this.api(), data;
					// start
					var sumImp = api
						.column( 2, {filter:'applied'} )
						.data()
						.reduce( function ( a, b ) {
							var x = parseInt(a.toString().replace(",","")) || 0;
							var y = parseInt(b.toString().replace(",","")) || 0;
							return parseInt(x)+ parseInt(y);

						} );
					$("#sumImp").html(addCommas(sumImp));
					
					var sumRTClicks = api
						.column( 3, {filter:'applied'}  )
						.data()
						.reduce( function ( a, b ) {
							var x = parseInt(a.toString().replace(",","")) || 0;
							var y = parseInt(b.toString().replace(",","")) || 0;
							return parseInt(x)+ parseInt(y);
						} );
					$("#sumRTClicks").html(addCommas(sumRTClicks));
					
					var sumRevClicks = api
						.column( 4, {filter:'applied'} )
						.data()
						.reduce( function ( a, b ) {
							var x = parseInt(a.toString().replace(",","")) || 0;
							var y = parseInt(b.replace(",","")) || 0;
							return parseInt(x)+ parseInt(y);
						} );
					$("#sumRevClicks").html(addCommas(sumRevClicks));
					
					var sumCTR = 0;
					if (parseInt(sumImp) > 0 && parseInt(sumRTClicks) > 0) {
						sumCTR = parseInt(sumRTClicks) / parseInt(sumImp);
					} else {
						if (parseInt(sumImp) > 0 && parseInt(sumRevClicks) > 0) {
							sumCTR = parseInt(sumRevClicks) / parseInt(sumImp);
						} else {
							sumCTR = 0;
						}
					}
					sumCTR = sumCTR*100;
					sumCTR = sumCTR.toFixed(2);
					$("#sumCTR").html(sumCTR+"%");
					
					var sumRev = api
						.column( 8, {filter:'applied'} )
						.data()
						.reduce( function ( a, b ) {
							var x = parseFloat(a.toString().replace(",","")) || 0;
							var y = parseFloat(b.replace(",","")) || 0;
							return parseFloat(x)+ parseFloat(y);
						} );
					sumRev = parseFloat(sumRev);
					$("#sumRev").html(addCommas("$"+sumRev.toFixed(2)));
					
					var sumSpend = api
						.column( 9, {filter:'applied'} )
						.data()
						.reduce( function ( a, b ) {
							var x = parseFloat(a.toString().replace(",","")) || 0;
							var y = parseFloat(b.replace(",","")) || 0;
							return parseFloat(x)+ parseFloat(y);
						} );
					 sumSpend = parseFloat(sumSpend);
					$("#sumSpend").html(addCommas("$"+sumSpend.toFixed(2)));
					
					var sumProfit = parseFloat(sumRev)-parseFloat(sumSpend);
					$("#sumProfit").html(addCommas("$"+sumProfit.toFixed(2)));
					
					var sumRPI = 0.00;  // revenue / imp
					var sumRPC = 0.00;  // revenue / rev clicks
					
					if (parseInt(sumImp) > 0) {sumRPI = parseFloat(sumRev) / parseInt(sumImp); }
					if (parseInt(sumRevClicks) > 0) {sumRPC = parseFloat(sumRev) / parseInt(sumRevClicks); }
					
					$("#sumRPI").html(addCommas("$"+sumRPI.toFixed(2)));
					$("#sumRPC").html(addCommas("$"+sumRPC.toFixed(2)));
					
					var sumROAS = 0.00;
					if (parseFloat(sumSpend) > 0) {
						sumROAS = parseFloat(sumRev)/parseFloat(sumSpend);
					}
					$("#sumROAS").html(addCommas(sumROAS.toFixed(2)));
					// end
				 }
			});
			
			
				
		});
		$("#breakdown").load("/dashboardBreakdown", function() {
			var groupColumn = 0;
			var tableTQProfit = $('#breakdownTable').DataTable({
				dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr  >>" +
            "<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
            "<'row'<'col-sm-12'> >" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				buttons: [
                {
                    extend:    'copyHtml5',
                    text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
                    title:	   'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
					
                },
                {
                    extend:    'csvHtml5',
                    text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
                    title:	   'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
					extend:    'excel',
					text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
					title:	   'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend:    'pdf',
                    text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
					orientation: 'landscape',
                	title:	   'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend: 	'print',
                    text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
                    autoPrint: 	false,
					title:	   	'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                }
				
    
            ],
				"order": [[ groupColumn, 'asc' ]],
				"displayLength": 50	,
				"columnDefs": [
					{						
						render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
						"targets": [1,2,3,4,5,6]
					}					
				]
							
			});
		});
		$("#tq5day").load("/dashboard5dayTQ", function() {
			var groupColumn = 0;
			var lowtqtable = $('#lowtqtable').DataTable({
				dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr  >>" +
            "<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
            "<'row'<'col-sm-12'> >" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				buttons: [
                {
                    extend:    'copyHtml5',
                    text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
                    title:	   'ASUI Low TQ Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
					
                },
                {
                    extend:    'csvHtml5',
                    text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
                    title:	   'ASUI Low TQ Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
					extend:    'excel',
					text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
					title:	   'ASUI Low TQ Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend:    'pdf',
                    text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
					orientation: 'landscape',
                	title:	   'ASUI Low TQ Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                },
                {
                    extend: 	'print',
                    text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
                    autoPrint: 	false,
					title:	   	'ASUI Low TQ Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
                }
				
    
            ],
				"order": [[ groupColumn, 'desc' ]],
				"displayLength": 50							
			});
			
			$(".kwmodallink").click(function () {
				var start = $("#startdate").val();
				var end = $("#enddate").val();
				
				var rgid = $(this).data('rgid');
				var rgname = $(this).data('rgname');
				//alert(rgid+"|"+rgname);
				
				rgname = encodeURIComponent(rgname);
				$("#kwmodalload").html("<img src=\"/img/ajax-loader.gif\">");
				$("#kwmodalload").load("/dashboardGetKW?startdate="+start+"&enddate="+end+"&rgid="+rgid+"&rgname="+rgname+"&active=1", function(){
					$("#kwsummary").load("/getKWSummaryHeader?id="+rgid+"&start="+start+"&end="+end, function(){
						$('.dynamicbar2').sparkline('html',{type: 'bar', barColor: 'blue', height: '40px', barWidth: 20, barSpacing: 3, zeroColor: '#b2b2b2'} );
					});
					$( "#tabs" ).tabs();
					// DATATABLE
					var lowtqtable = $('#dashboardkwdt').DataTable({
						"columnDefs": [
							{ "sortable": false, "targets": [11,12,13] },
							{						
								render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
								"targets": [7]
							}
						],
						"oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><b>Search&nbsp;&nbsp;</b><i class="fa fa-search"></i></a>' },
						dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'" +
						"><'col-sm-7' <\"#actionSelectDiv\">> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						buttons: [
							{
								extend:    'copyHtml5',
								text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
								titleAttr: 'Copy'
							},
							{
								extend:    'csvHtml5',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
								titleAttr: 'CSV'
							},
							{
							  extend:    'excel',
							  text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
							  titleAttr: 'Excel'
							},
							{
								extend:    'pdfHtml5',
								text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
								titleAttr: 'PDF'
							},
							{
								extend: 'print',
								text: '<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
								autoPrint: false
							}
							],
					 "footerCallback": function ( row, data, start, end, display ) {
						var api = this.api(), data;
						// start
						var sumImp = api
							.column( 1, {filter:'applied'} )
							.data()
							.reduce( function ( a, b ) {
								var x = parseFloat(a) || 0;
								var y = parseFloat(b) || 0;
								return x + y;
							} );
						$("#kwtotal_imps").html(addCommas(sumImp));
						var sumRTClicks = api
							.column( 2 )
							.data()
							.reduce( function ( a, b ) {
								var x = parseFloat(a) || 0;
								var y = parseFloat(b) || 0;
								return x + y;
							} );
						$("#kwtotal_rtclicks").html(addCommas(sumRTClicks));
						
						var sumRevClicks = api
							.column( 3, {filter:'applied'} )
							.data()
							.reduce( function ( a, b ) {
								var x = parseFloat(a) || 0;
								var y = parseFloat(b) || 0;
								return x + y;
							} );
						$("#kwtotal_revclicks").html(addCommas(sumRevClicks));
						
						var sumCTR = 0;
						if (parseInt(sumImp) > 0 && parseInt(sumRTClicks) > 0) {
							sumCTR = parseInt(sumRTClicks) / parseInt(sumImp);
						} else {
							if (parseInt(sumImp) > 0 && parseInt(sumRevClicks) > 0) {
								sumCTR = parseInt(sumRevClicks) / parseInt(sumImp);
							} else {
								sumCTR = 0;
							}
						}
						sumCTR = sumCTR*100;
						sumCTR = sumCTR.toFixed(2);
						$("#kwtotal_ctr").html(sumCTR+"%");
						
						var sumRev = api
							.column( 7, {filter:'applied'} )
							.data()
							.reduce( function ( a, b ) {
								var x = parseFloat(a) || 0;
								var y = parseFloat(b) || 0;
								return x + y;
							} );
						$("#kwtotal_rev").html(addCommas("$"+sumRev.toFixed(2)));
						
						var sumRPI = 0.00;  // revenue / imp
						var sumRPC = 0.00;  // revenue / rev clicks
						
						if (parseInt(sumImp) > 0) {sumRPI = parseFloat(sumRev) / parseInt(sumImp); }
						if (parseInt(sumRevClicks) > 0) {sumRPC = parseFloat(sumRev) / parseInt(sumRevClicks); }
						
						$("#kwtotal_rpi").html(addCommas("$"+sumRPI.toFixed(2)));
						$("#kwtotal_rpc").html(addCommas("$"+sumRPC.toFixed(2)));
						
						
						// end
					 }
					});					
					// END DATATABLE
					// LOAD AUTOWEIGHTER
					$.ajax({
						method: "GET",
						url: "/getautoweight",
						data: {'rgid' : $("#kwrgid").val()}
					  })
						.done(function( msg ) {
						  if (msg == "1") {
							$( "#autoweighter" ).prop( "checked", true );
						  } else {
							$( "#autoweighter" ).prop( "checked", false );
						  }
						});
					// END WEIGHTER
					
					KW_drawActionElements();
					$("#allweight").hide();
					var HRPCLoader = window.setInterval(LoadingHistoricRPC, 200);
					// GET SUGGEST
					$.ajax({
						url : "/json/getKeywordSuggestionsJSON",
						type: "GET",
						data : 'rev_group_id=' + $("#kwrgid").val(),
						success:function(data, textStatus, jqXHR)
						{
							var innerData = "<table style='width:100%;'>";
							var idx = 0;
							for (var i in data) {
								if(idx % 3 == 0){
									innerData = innerData + "<tr>";
								}
								innerData = innerData + '<td style="padding: 0px 0px 0px 6px;vertical-align: bottom;">'
									+ '<input class="addSuggest"  id="addSuggest_' + idx + '" type="checkbox" value="'
									+ data[i].keyword + '"><label for="addSuggest_' + idx + '"><span style="vertical-align: top;">'
									+ '&nbsp;&nbsp;' + '$' + data[i].rpc + '&nbsp;&nbsp;' +  data[i].keyword
									+ '</span></label></td>';
								idx++;
								if(idx % 3 == 0){
									innerData = innerData + "</tr>";
								}

							}
							innerData = innerData + "</table>";
							clearInterval(HRPCLoader);
							if($.isEmptyObject(data)){
								$("#suggestedKeywordsListDiv").html('No keyword suggestions');
							} else {
								$("#suggestedKeywordsListDiv").html(innerData);
							}
							$('#addHistoricKeywordsButton').show();
							$('#keywordSuggestionButton').hide();
						},
						error: function(jqXHR, textStatus, errorThrown)
						{
							alert("Error in get keyword suggest ajax contact dev team with this message.");
						}
					});
					// END SUGGEST
					$("#rgnotes_list").load("/getRevGroupNotesList?rev_group_id="+$("#kwrgid").val());
				});
				
			
			});
		});
		
		
		
		
    });
	
	
	function runROAS() {
		var start = $("#startdate").val();
		var end = $("#enddate").val();
		var roasspend = $("#roasspend").val();
		var roasnum = $("#roasnum").val();
		
		var userid = "";
		$.ajax({
			method: "GET",
			url: "/dashboardGetUserID"
		  })
			.done(function( msg ) {
				userid = msg;
				if (userid != "") {
					$("#chartROASTodayvsYesterday").html("<img src=\"/img/ajax-loader.gif\">");
					$("#chartROASTodayvsYesterday").load("/dashboardROAS?startdate="+start+"&enddate="+end+"&roasspend="+roasspend+"&roasnum="+roasnum);					
				} else {
					window.location = "/login";
				}
			});
		
		
	}
	
	function runSearchTrafficByAccount() {
		var summaryStartDate = $("#startdate").val();
		var summaryEndDate = $("#enddate").val();
		var start = $("#startdate").val();
		var end = $("#enddate").val();
		var roasspend = $("#roasspend").val();
		
		var userid = "";
		$.ajax({
			method: "GET",
			url: "/dashboardGetUserID"
		  })
			.done(function( msg ) {
				userid = msg;
				if (userid != "") {
					// RUN SEARCH
					$("#chartRevenueByTrafficAccount").html("<img src=\"/img/ajax-loader.gif\">");
					$("#chartRevenueByTrafficAccount").load("/dashboardRevenueByTrafficAccount?startdate="+start+"&enddate="+end, function() {
						$( "#startdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
						$( "#enddate" ).datepicker({ dateFormat: 'yy-mm-dd' });	
						if ($.fn.dataTable.isDataTable('#rbtaTable')) {
							var dtable = $('#rbtaTable').DataTable();
							dtable.destroy();
						}
						var groupColumn = 0;
						var table = $('#rbtaTable').DataTable({
							dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr  >>" +
						"<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
						"<'row'<'col-sm-12'> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
							buttons: [
							{
								extend:    'copyHtml5',
								text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
								title:	   'ASUI Revenue By Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
								
							},
							{
								extend:    'csvHtml5',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
								title:	   'ASUI Revenue By Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'excel',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
								title:	   'ASUI Revenue By Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'pdf',
								text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
								orientation: 'landscape',
								title:	   'ASUI Revenue By Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend: 	'print',
								text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
								autoPrint: 	false,
								title:	   	'ASUI Revenue By Traffic Account Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							}
							
				
						],
							"columnDefs": [
								{ "visible": false, "targets": groupColumn }
							],
							"order": [[ groupColumn, 'asc' ]],
							"displayLength": 50,
							"drawCallback": function ( settings ) {
								var api = this.api();
								var rows = api.rows( {page:'current'} ).nodes();
								var last=null;
					 
								api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
									if ( last !== group ) {
										$(rows).eq( i ).before(
											'<tr class="group bg-success"><td colspan="5">'+group+'</td></tr>'
										);
					 
										last = group;
									}
								} );
							}
						} );
						
						// Order by the grouping
						$('#rbtaTable tbody').on( 'click', 'tr.group', function () {
							var currentOrder = table.order()[0];
							if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
								table.order( [ groupColumn, 'desc' ] ).draw();
							}
							else {
								table.order( [ groupColumn, 'asc' ] ).draw();
							}
						} );
						
					});	
					
					$("#tqprofit").html("<img src=\"/img/ajax-loader.gif\">");
					$("#tqprofit").load("/dashboardTQProfit", function() {
						var groupColumn = 8;
						var tableTQProfit = $('#tableTQProfit').DataTable({
							dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr  >>" +
						"<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
						"<'row'<'col-sm-12'> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
							buttons: [
							{
								extend:    'copyHtml5',
								text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
								title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
								
							},
							{
								extend:    'csvHtml5',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
								title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'excel',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
								title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'pdf',
								text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
								orientation: 'landscape',
								title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend: 	'print',
								text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
								autoPrint: 	false,
								title:	   	'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							}
							
				
						],
							"order": [[ groupColumn, 'desc' ]],
							"displayLength": 50	,
							"columnDefs": [
								{						
									render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
									"targets": [6,7,8,9,10]
								}					
							],
							
							 "footerCallback": function ( row, data, start, end, display ) {
								var api = this.api(), data;
								// start
								var sumImp = api
									.column( 2, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumImp").html(addCommas(sumImp));
								
								var sumRTClicks = api
									.column( 3 )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumRTClicks").html(addCommas(sumRTClicks));
								
								var sumRevClicks = api
									.column( 4, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumRevClicks").html(addCommas(sumRevClicks));
								
								var sumCTR = 0;
								if (parseInt(sumImp) > 0 && parseInt(sumRTClicks) > 0) {
									sumCTR = parseInt(sumRTClicks) / parseInt(sumImp);
								} else {
									if (parseInt(sumImp) > 0 && parseInt(sumRevClicks) > 0) {
										sumCTR = parseInt(sumRevClicks) / parseInt(sumImp);
									} else {
										sumCTR = 0;
									}
								}
								sumCTR = sumCTR*100;
								sumCTR = sumCTR.toFixed(2);
								$("#sumCTR").html(sumCTR+"%");
								
								var sumRev = api
									.column( 8, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumRev").html(addCommas("$"+sumRev.toFixed(2)));
								
								var sumSpend = api
									.column( 9, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumSpend").html(addCommas("$"+sumSpend.toFixed(2)));
								
								var sumProfit = parseFloat(sumRev)-parseFloat(sumSpend);
								$("#sumProfit").html(addCommas("$"+sumProfit.toFixed(2)));
								
								var sumRPI = 0.00;  // revenue / imp
								var sumRPC = 0.00;  // revenue / rev clicks
								
								if (parseInt(sumImp) > 0) {sumRPI = parseFloat(sumRev) / parseInt(sumImp); }
								if (parseInt(sumRevClicks) > 0) {sumRPC = parseFloat(sumRev) / parseInt(sumRevClicks); }
								
								$("#sumRPI").html(addCommas("$"+sumRPI.toFixed(2)));
								$("#sumRPC").html(addCommas("$"+sumRPC.toFixed(2)));
								
								var sumROAS = 0.00;
								if (parseFloat(sumSpend) > 0) {
									sumROAS = parseFloat(sumRev)/parseFloat(sumSpend);
								}
								$("#sumROAS").html(addCommas(sumROAS.toFixed(2)));
								// end
							 }
						});
						
						
							
					});
					$("#chartROASTodayvsYesterday").html("<img src=\"/img/ajax-loader.gif\">");
					$("#chartROASTodayvsYesterday").load("/dashboardROAS?startdate="+start+"&enddate="+end+"&roasspend="+roasspend);
					$("#chartRevenue").html("<img src=\"/img/ajax-loader.gif\">");
					$("#chartRevenue").load("/dashboardRevenue?startdate="+start+"&enddate="+end);
					$("#scrubRate").html("<img src=\"/img/ajax-loader.gif\">");
					$("#scrubRate").load("/dashboardScrub?startdate="+start+"&enddate="+end, function() {
						var groupColumn = 3;
						var tableScrub = $('#tableScrub').DataTable({
							dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr  >>" +
						"<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
						"<'row'<'col-sm-12'> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
							buttons: [
							{
								extend:    'copyHtml5',
								text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
								title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
								
							},
							{
								extend:    'csvHtml5',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
								title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'excel',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
								title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'pdf',
								text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
								orientation: 'landscape',
								title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend: 	'print',
								text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
								autoPrint: 	false,
								title:	   	'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							}
							
				
						],
							"order": [[ groupColumn, 'asc' ]],
							"displayLength": 50				
						});
					});
					$("#breakdown").html("<img src=\"/img/ajax-loader.gif\">");
					$("#breakdown").load("/dashboardBreakdown?startdate="+start+"&enddate="+end, function() {
						var groupColumn = 0;
						var tableTQProfit = $('#breakdownTable').DataTable({
							dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr  >>" +
						"<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
						"<'row'<'col-sm-12'> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
							buttons: [
							{
								extend:    'copyHtml5',
								text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
								title:	   'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
								
							},
							{
								extend:    'csvHtml5',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
								title:	   'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'excel',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
								title:	   'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'pdf',
								text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
								orientation: 'landscape',
								title:	   'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend: 	'print',
								text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
								autoPrint: 	false,
								title:	   	'ASUI Breakdown Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							}
							
				
						],
							"order": [[ groupColumn, 'asc' ]],
							"displayLength": 50	,
							"columnDefs": [
								{						
									render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
									"targets": [1,2,3,4,5,6]
								}					
							]
										
						});
					});
					$("#tq5day").html("<img src=\"/img/ajax-loader.gif\">");
					$("#tq5day").load("/dashboard5dayTQ?startdate="+start+"&enddate="+end, function() {
						var groupColumn = 0;
						var lowtqtable = $('#lowtqtable').DataTable({
							"order": [[ groupColumn, 'desc' ]],
							"displayLength": 50							
						});
						
						$(".kwmodallink").click(function () {
							var start = $("#startdate").val();
							var end = $("#enddate").val();
							
							var rgid = $(this).data('rgid');
							var rgname = $(this).data('rgname');
							//alert(rgid+"|"+rgname);
							
							rgname = encodeURIComponent(rgname);
							$("#kwmodalload").html("<img src=\"/img/ajax-loader.gif\">");
							$("#kwmodalload").load("/dashboardGetKW?startdate="+start+"&enddate="+end+"&rgid="+rgid+"&rgname="+rgname+"&active=1", function(){
								$("#kwsummary").load("/getKWSummaryHeader?id="+rgid+"&start="+start+"&end="+end, function(){
									$('.dynamicbar2').sparkline('html',{type: 'bar', barColor: 'blue', height: '40px', barWidth: 20, barSpacing: 3, zeroColor: '#b2b2b2'} );
								});
								$( "#tabs" ).tabs();
								// DATATABLE
								var lowtqtable = $('#dashboardkwdt').DataTable({
									"columnDefs": [
										{ "sortable": false, "targets": [11,12,13] },
										{						
											render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
											"targets": [7]
										}
									],
									"oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><b>Search&nbsp;&nbsp;</b><i class="fa fa-search"></i></a>' },
									dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
									"<'row'<'col-sm-12'tr>>" +
									"<'row'<'col-sm-5'" +
									"><'col-sm-7' <\"#actionSelectDiv\">> >" +
									"<'row'<'col-sm-5'i><'col-sm-7'p>>",
									buttons: [
										{
											extend:    'copyHtml5',
											text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
											titleAttr: 'Copy'
										},
										{
											extend:    'csvHtml5',
											text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
											titleAttr: 'CSV'
										},
										{
										  extend:    'excel',
										  text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
										  titleAttr: 'Excel'
										},
										{
											extend:    'pdfHtml5',
											text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
											titleAttr: 'PDF'
										},
										{
											extend: 'print',
											text: '<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
											autoPrint: false
										}
										],
								 "footerCallback": function ( row, data, start, end, display ) {
									var api = this.api(), data;
									// start
									var sumImp = api
										.column( 1, {filter:'applied'} )
										.data()
										.reduce( function ( a, b ) {
											var x = parseFloat(a) || 0;
											var y = parseFloat(b) || 0;
											return x + y;
										} );
									$("#kwtotal_imps").html(addCommas(sumImp));
									var sumRTClicks = api
										.column( 2 )
										.data()
										.reduce( function ( a, b ) {
											var x = parseFloat(a) || 0;
											var y = parseFloat(b) || 0;
											return x + y;
										} );
									$("#kwtotal_rtclicks").html(addCommas(sumRTClicks));
									
									var sumRevClicks = api
										.column( 3, {filter:'applied'} )
										.data()
										.reduce( function ( a, b ) {
											var x = parseFloat(a) || 0;
											var y = parseFloat(b) || 0;
											return x + y;
										} );
									$("#kwtotal_revclicks").html(addCommas(sumRevClicks));
									
									var sumCTR = 0;
									if (parseInt(sumImp) > 0 && parseInt(sumRTClicks) > 0) {
										sumCTR = parseInt(sumRTClicks) / parseInt(sumImp);
									} else {
										if (parseInt(sumImp) > 0 && parseInt(sumRevClicks) > 0) {
											sumCTR = parseInt(sumRevClicks) / parseInt(sumImp);
										} else {
											sumCTR = 0;
										}
									}
									sumCTR = sumCTR*100;
									sumCTR = sumCTR.toFixed(2);
									$("#kwtotal_ctr").html(sumCTR+"%");
									
									var sumRev = api
										.column( 7, {filter:'applied'} )
										.data()
										.reduce( function ( a, b ) {
											var x = parseFloat(a) || 0;
											var y = parseFloat(b) || 0;
											return x + y;
										} );
									$("#kwtotal_rev").html(addCommas("$"+sumRev.toFixed(2)));
									
									var sumRPI = 0.00;  // revenue / imp
									var sumRPC = 0.00;  // revenue / rev clicks
									
									if (parseInt(sumImp) > 0) {sumRPI = parseFloat(sumRev) / parseInt(sumImp); }
									if (parseInt(sumRevClicks) > 0) {sumRPC = parseFloat(sumRev) / parseInt(sumRevClicks); }
									
									$("#kwtotal_rpi").html(addCommas("$"+sumRPI.toFixed(2)));
									$("#kwtotal_rpc").html(addCommas("$"+sumRPC.toFixed(2)));
									
									
									// end
								 }
								});					
								// END DATATABLE
								// LOAD AUTOWEIGHTER
								$.ajax({
									method: "GET",
									url: "/getautoweight",
									data: {'rgid' : $("#kwrgid").val()}
								  })
									.done(function( msg ) {
									  if (msg == "1") {
										$( "#autoweighter" ).prop( "checked", true );
									  } else {
										$( "#autoweighter" ).prop( "checked", false );
									  }
									});
								// END WEIGHTER
								
								KW_drawActionElements();
								$("#allweight").hide();
								var HRPCLoader = window.setInterval(LoadingHistoricRPC, 200);
								// GET SUGGEST
								$.ajax({
									url : "/json/getKeywordSuggestionsJSON",
									type: "GET",
									data : 'rev_group_id=' + $("#kwrgid").val(),
									success:function(data, textStatus, jqXHR)
									{
										var innerData = "<table style='width:100%;'>";
										var idx = 0;
										for (var i in data) {
											if(idx % 3 == 0){
												innerData = innerData + "<tr>";
											}
											innerData = innerData + '<td style="padding: 0px 0px 0px 6px;vertical-align: bottom;">'
												+ '<input class="addSuggest"  id="addSuggest_' + idx + '" type="checkbox" value="'
												+ data[i].keyword + '"><label for="addSuggest_' + idx + '"><span style="vertical-align: top;">'
												+ '&nbsp;&nbsp;' + '$' + data[i].rpc + '&nbsp;&nbsp;' +  data[i].keyword
												+ '</span></label></td>';
											idx++;
											if(idx % 3 == 0){
												innerData = innerData + "</tr>";
											}

										}
										innerData = innerData + "</table>";
										clearInterval(HRPCLoader);
										if($.isEmptyObject(data)){
											$("#suggestedKeywordsListDiv").html('No keyword suggestions');
										} else {
											$("#suggestedKeywordsListDiv").html(innerData);
										}
										$('#addHistoricKeywordsButton').show();
										$('#keywordSuggestionButton').hide();
									},
									error: function(jqXHR, textStatus, errorThrown)
									{
										alert("Error in get keyword suggest ajax contact dev team with this message.");
									}
								});
								// END SUGGEST
								$("#rgnotes_list").load("/getRevGroupNotesList?rev_group_id="+$("#kwrgid").val());
							});
							
						
						});
					});
					// END SEARCH
					
				} else {
					window.location = "/login";
				}
			});
			
		
		
		
	}
	
    function htmlEncode(value){
      return escape(value);
    }
	
	function openRGScrub(ta) {
		var summaryStartDate = $("#startdate").val();
		var summaryEndDate = $("#enddate").val();
		
		var userid = "";
		$.ajax({
			method: "GET",
			url: "/dashboardGetUserID"
		  })
			.done(function( msg ) {
				userid = msg;
				if (userid != "") {
					//dashboardScrubByRG
					$("#modalload").load("/dashboardScrubByRG?ta="+ta, function() {
						var groupColumn = 3;
						var tableScrub = $('#modalScrub').DataTable({
							dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr  >>" +
						"<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
						"<'row'<'col-sm-12'> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
							buttons: [
							{
								extend:    'copyHtml5',
								text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
								title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
								
							},
							{
								extend:    'csvHtml5',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
								title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'excel',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
								title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'pdf',
								text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
								orientation: 'landscape',
								title:	   'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend: 	'print',
								text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
								autoPrint: 	false,
								title:	   	'ASUI Scrub Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							}
							
				
						],
							"order": [[ groupColumn, 'desc' ]],
							"displayLength": 25				
						});
						
						$(".kwmodallink").click(function () {
							var start = $("#startdate").val();
							var end = $("#enddate").val();
							
							var rgid = $(this).data('rgid');
							var rgname = $(this).data('rgname');
							//alert(rgid+"|"+rgname);
							
							rgname = encodeURIComponent(rgname);
							$("#kwmodalload").html("<img src=\"/img/ajax-loader.gif\">");
							$("#kwmodalload").load("/dashboardGetKW?startdate="+start+"&enddate="+end+"&rgid="+rgid+"&rgname="+rgname+"&active=1", function(){
								$("#kwsummary").load("/getKWSummaryHeader?id="+rgid+"&start="+start+"&end="+end, function(){
									$('.dynamicbar2').sparkline('html',{type: 'bar', barColor: 'blue', height: '40px', barWidth: 20, barSpacing: 3, zeroColor: '#b2b2b2'} );
								});
								$( "#tabs" ).tabs();
								// DATATABLE
								var lowtqtable = $('#dashboardkwdt').DataTable({
									"columnDefs": [
										{ "sortable": false, "targets": [11,12,13] },
										{						
											render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
											"targets": [7]
										}
									],
									"oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><b>Search&nbsp;&nbsp;</b><i class="fa fa-search"></i></a>' },
									dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
									"<'row'<'col-sm-12'tr>>" +
									"<'row'<'col-sm-5'" +
									"><'col-sm-7' <\"#actionSelectDiv\">> >" +
									"<'row'<'col-sm-5'i><'col-sm-7'p>>",
									buttons: [
										{
											extend:    'copyHtml5',
											text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
											titleAttr: 'Copy'
										},
										{
											extend:    'csvHtml5',
											text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
											titleAttr: 'CSV'
										},
										{
										  extend:    'excel',
										  text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
										  titleAttr: 'Excel'
										},
										{
											extend:    'pdfHtml5',
											text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
											titleAttr: 'PDF'
										},
										{
											extend: 'print',
											text: '<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
											autoPrint: false
										}
										],
								 "footerCallback": function ( row, data, start, end, display ) {
									var api = this.api(), data;
									// start
									var sumImp = api
										.column( 1, {filter:'applied'} )
										.data()
										.reduce( function ( a, b ) {
											var x = parseFloat(a) || 0;
											var y = parseFloat(b) || 0;
											return x + y;
										} );
									$("#kwtotal_imps").html(addCommas(sumImp));
									var sumRTClicks = api
										.column( 2 )
										.data()
										.reduce( function ( a, b ) {
											var x = parseFloat(a) || 0;
											var y = parseFloat(b) || 0;
											return x + y;
										} );
									$("#kwtotal_rtclicks").html(addCommas(sumRTClicks));
									
									var sumRevClicks = api
										.column( 3, {filter:'applied'} )
										.data()
										.reduce( function ( a, b ) {
											var x = parseFloat(a) || 0;
											var y = parseFloat(b) || 0;
											return x + y;
										} );
									$("#kwtotal_revclicks").html(addCommas(sumRevClicks));
									
									var sumCTR = 0;
									if (parseInt(sumImp) > 0 && parseInt(sumRTClicks) > 0) {
										sumCTR = parseInt(sumRTClicks) / parseInt(sumImp);
									} else {
										if (parseInt(sumImp) > 0 && parseInt(sumRevClicks) > 0) {
											sumCTR = parseInt(sumRevClicks) / parseInt(sumImp);
										} else {
											sumCTR = 0;
										}
									}
									sumCTR = sumCTR*100;
									sumCTR = sumCTR.toFixed(2);
									$("#kwtotal_ctr").html(sumCTR+"%");
									
									var sumRev = api
										.column( 7, {filter:'applied'} )
										.data()
										.reduce( function ( a, b ) {
											var x = parseFloat(a) || 0;
											var y = parseFloat(b) || 0;
											return x + y;
										} );
									$("#kwtotal_rev").html(addCommas("$"+sumRev.toFixed(2)));
									
									var sumRPI = 0.00;  // revenue / imp
									var sumRPC = 0.00;  // revenue / rev clicks
									
									if (parseInt(sumImp) > 0) {sumRPI = parseFloat(sumRev) / parseInt(sumImp); }
									if (parseInt(sumRevClicks) > 0) {sumRPC = parseFloat(sumRev) / parseInt(sumRevClicks); }
									
									$("#kwtotal_rpi").html(addCommas("$"+sumRPI.toFixed(2)));
									$("#kwtotal_rpc").html(addCommas("$"+sumRPC.toFixed(2)));
									
									
									// end
								 }
								});					
								// END DATATABLE
								// LOAD AUTOWEIGHTER
								$.ajax({
									method: "GET",
									url: "/getautoweight",
									data: {'rgid' : $("#kwrgid").val()}
								  })
									.done(function( msg ) {
									  if (msg == "1") {
										$( "#autoweighter" ).prop( "checked", true );
									  } else {
										$( "#autoweighter" ).prop( "checked", false );
									  }
									});
								// END WEIGHTER
								
								KW_drawActionElements();
								$("#allweight").hide();
								var HRPCLoader = window.setInterval(LoadingHistoricRPC, 200);
								// GET SUGGEST
								$.ajax({
									url : "/json/getKeywordSuggestionsJSON",
									type: "GET",
									data : 'rev_group_id=' + $("#kwrgid").val(),
									success:function(data, textStatus, jqXHR)
									{
										var innerData = "<table style='width:100%;'>";
										var idx = 0;
										for (var i in data) {
											if(idx % 3 == 0){
												innerData = innerData + "<tr>";
											}
											innerData = innerData + '<td style="padding: 0px 0px 0px 6px;vertical-align: bottom;">'
												+ '<input class="addSuggest"  id="addSuggest_' + idx + '" type="checkbox" value="'
												+ data[i].keyword + '"><label for="addSuggest_' + idx + '"><span style="vertical-align: top;">'
												+ '&nbsp;&nbsp;' + '$' + data[i].rpc + '&nbsp;&nbsp;' +  data[i].keyword
												+ '</span></label></td>';
											idx++;
											if(idx % 3 == 0){
												innerData = innerData + "</tr>";
											}

										}
										innerData = innerData + "</table>";
										clearInterval(HRPCLoader);
										if($.isEmptyObject(data)){
											$("#suggestedKeywordsListDiv").html('No keyword suggestions');
										} else {
											$("#suggestedKeywordsListDiv").html(innerData);
										}
										$('#addHistoricKeywordsButton').show();
										$('#keywordSuggestionButton').hide();
									},
									error: function(jqXHR, textStatus, errorThrown)
									{
										alert("Error in get keyword suggest ajax contact dev team with this message.");
									}
								});
								// END SUGGEST
								$("#rgnotes_list").load("/getRevGroupNotesList?rev_group_id="+$("#kwrgid").val());
							});
							
						
						});
					});
					return true;
				} else {
					window.location = "/login";
				}
			});
		
	}

	function doProfitQuery() {
		var start = $("#startdate").val();
		var end = $("#enddate").val();
		var fltrRevenue = $("#revs").val();
		var fltrRevenueComp = $("#revcomp").val();
		var fltrRPCcomp = $("#rpccomp").val();
		var fltrRPC = $("#rpcfilter").val();
		var fltrClicksCom = $("#clickscomp").val();
		var fltrClicks = $("#clicksfilter").val();
		var fltrRPIComp = $("#rpicomp").val();
		var fltrRPI = $("#rpifilter").val();
		var fltrImpComp = $("#impcomp").val();
		var fltrImp = $("#impfilter").val();
		var fltrRoasComp = $("#roascomp").val();
		var fltrRoas = $("#roasfilter").val();
		var fltrDwtComp = $("#dwtcomp").val();
		var fltrDwt = $("#dwtfilter").val();
		var summaryStartDate = $("#startdate").val();
		var summaryEndDate = $("#enddate").val();
		
		var fltrAll = "fltrRevenue="+fltrRevenue+"&fltrRevenueComp="+fltrRevenueComp+"&fltrRPCcomp="+fltrRPCcomp+"&fltrRPC="+fltrRPC+"&fltrClicksCom="+fltrClicksCom+"&fltrClicks="+fltrClicks+"&fltrRPIComp="+fltrRPIComp+"&fltrRPI="+fltrRPI+"&fltrRPIComp="+fltrRPIComp+"&fltrImpComp="+fltrImpComp+"&fltrImp="+fltrImp+"&fltrRoas="+fltrRoas+"&fltrRoasComp="+fltrRoasComp+"&fltrDwt="+fltrDwt+"&fltrDwtComp="+fltrDwtComp;
		
		var userid = "";
		$.ajax({
			method: "GET",
			url: "/dashboardGetUserID"
		  })
			.done(function( msg ) {
				userid = msg;
				if (userid != "") {
					$("#tqprofit").html("<img src=\"/img/ajax-loader.gif\">");
					$("#tqprofit").load("/dashboardTQProfit?startdate="+start+"&enddate="+end+"&"+fltrAll, function() {
						var groupColumn = 8;
						var tableTQProfit = $('#tableTQProfit').DataTable({
							dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr  >>" +
						"<'row'<'col-sm-12' <\"#actionsDiv\"> > >" +
						"<'row'<'col-sm-12'> >" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
							buttons: [
							{
								extend:    'copyHtml5',
								text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
								title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
								
							},
							{
								extend:    'csvHtml5',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
								title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'excel',
								text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
								title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend:    'pdf',
								text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
								orientation: 'landscape',
								title:	   'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							},
							{
								extend: 	'print',
								text: 		'<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
								autoPrint: 	false,
								title:	   	'ASUI Summary Report - From: '+summaryStartDate+ ' to ' +summaryEndDate
							}
							
				
						],
							"order": [[ groupColumn, 'desc' ]],
							"displayLength": 50	,
							"columnDefs": [
								{						
									render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
									"targets": [6,7,8,9,10]
								}					
							],
							"footerCallback": function ( row, data, start, end, display ) {
								var api = this.api(), data;
								// start
								var sumImp = api
									.column( 2, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );

								$("#sumImp").html(addCommas(sumImp));

								var sumRTClicks = api
									.column( 3 )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumRTClicks").html(addCommas(sumRTClicks));
								
								var sumRevClicks = api
									.column( 4, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumRevClicks").html(addCommas(sumRevClicks));
								
								var sumCTR = 0;
								if (parseInt(sumImp) > 0 && parseInt(sumRTClicks) > 0) {
									sumCTR = parseInt(sumRTClicks) / parseInt(sumImp);
								} else {
									if (parseInt(sumImp) > 0 && parseInt(sumRevClicks) > 0) {
										sumCTR = parseInt(sumRevClicks) / parseInt(sumImp);
									} else {
										sumCTR = 0;
									}
								}
								sumCTR = sumCTR*100;
								sumCTR = sumCTR.toFixed(2);
								$("#sumCTR").html(sumCTR+"%");
								
								var sumRev = api
									.column( 8, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumRev").html(addCommas("$"+sumRev.toFixed(2)));
								
								var sumSpend = api
									.column( 9, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#sumSpend").html(addCommas("$"+sumSpend.toFixed(2)));
								
								var sumProfit = parseFloat(sumRev)-parseFloat(sumSpend);
								$("#sumProfit").html(addCommas("$"+sumProfit.toFixed(2)));
								
								var sumRPI = 0.00;  // revenue / imp
								var sumRPC = 0.00;  // revenue / rev clicks
								
								if (parseInt(sumImp) > 0) {sumRPI = parseFloat(sumRev) / parseInt(sumImp); }
								if (parseInt(sumRevClicks) > 0) {sumRPC = parseFloat(sumRev) / parseInt(sumRevClicks); }
								
								$("#sumRPI").html(addCommas("$"+sumRPI.toFixed(2)));
								$("#sumRPC").html(addCommas("$"+sumRPC.toFixed(2)));
								
								var sumROAS = 0.00;
								if (parseFloat(sumSpend) > 0) {
									sumROAS = parseFloat(sumRev)/parseFloat(sumSpend);
								}
								$("#sumROAS").html(addCommas(sumROAS.toFixed(2)));
								// end
							 }				
						});
						
						
						
						$("#revs").val(fltrRevenue);
						$("#revcomp").val(fltrRevenueComp);
						$("#rpccomp").val(fltrRPCcomp);
						$("#rpcfilter").val(fltrRPC);
						$("#clickscomp").val(fltrClicksCom);
						$("#clicksfilter").val(fltrClicks);
						$("#rpicomp").val(fltrRPIComp);
						$("#rpifilter").val(fltrRPI);
						$("#impcomp").val(fltrImpComp);
						$("#impfilter").val(fltrImp);
						$("#roascomp").val(fltrRoasComp);
						$("#roasfilter").val(fltrRoas);
						$("#dwtcomp").val(fltrDwtComp);
						$("#dwtfilter").val(fltrDwt);
					});
				} else {
					window.location = "/login";
				}
			});
			
		
	}
    
	function openhome(traf_id) {
		$.ajax({
			  method: "GET",
			  url: "/dashboardSetTrafficID",
			  data: { traffic_id: traf_id }
			})
			  .done(function( msg ) {
				 var win = window.open('/home', '_blank');
				 win.focus();
				 
			  });
	}
	
	function changeKWStatus(rgid,kwname,activeStatus) {
		 if (confirm('Are you sure you wish to change status of keyword: '+kwname+'?')) {
			 $.ajax({
				url : "changeKeywordStatus",
				type: "GET",
				data : 'rev_group_id=' + rgid
				+ '&keyword_name=' + encodeURIComponent(kwname)
				+ '&resolution_id=' + $("#kwresolution").val()
				+ '&statusKeywordActiveKeyword=' + activeStatus,
				success:function(data, textStatus, jqXHR)
				{
					refreshKWPanel();
				}
			});		
		}
	}
	
	function resetKW(rgid,kwname) {
		if (confirm('Are you sure you wish to reset this keyword weight: '+kwname+'?')) {
			if($("#kwresolution").val() == "ALL"){
				$.ajax({
					url : "resetKeywordUpdate",
					type: "GET",
					data : 'rev_group_id=' + rgid + '&keyword_name=' + encodeURIComponent(kwname) +
					'&resolution_id=' + 0,
				});
				$.ajax({
					url : "resetKeywordUpdate",
					type: "GET",
					data : 'rev_group_id=' + rgid + '&keyword_name=' + encodeURIComponent(kwname) +
					'&resolution_id=' + 1,
					success:function(data, textStatus, jqXHR)
					{
						refreshKWPanel();
					}
				});
			} else {
				$.ajax(
					{
						url : "resetKeywordUpdate",
						type: "GET",
						data : 'rev_group_id=' + rgid
						+ '&keyword_name=' + encodeURIComponent(kwname) +
						'&resolution_id=' + $("#kwresolution").val(),
						success:function(data, textStatus, jqXHR)
						{
							refreshKWPanel();
						}
					});
			}
		}
		
	}
	
	function refreshKWPanel() {
		var start = $("#startdate").val();
		var end = $("#enddate").val();
		
		var rgid = $('#kwrgid').val();
		var rgname = $('#kwrgname').val();
		var active_only = 1; 
		var res = $("#kwresolution").val();
		if($("#show_inactive_keywords").is(':checked')) {
			active_only = 0;
			//alert("show inactive!");
		}
		
		rgname = encodeURIComponent(rgname);
		
		var userid = "";
		$.ajax({
			method: "GET",
			url: "/dashboardGetUserID"
		  })
			.done(function( msg ) {
				userid = msg;
				if (userid != "") {
					$("#kwmodalload").html("<img src=\"/img/ajax-loader.gif\">");
					$("#kwmodalload").load("/dashboardGetKW?startdate="+start+"&enddate="+end+"&rgid="+rgid+"&rgname="+rgname+"&active="+active_only+"&res="+res, function(){
						$("#kwsummary").load("/getKWSummaryHeader?id="+rgid+"&start="+start+"&end="+end, function(){
								$('.dynamicbar2').sparkline('html',{type: 'bar', barColor: 'blue', height: '40px', barWidth: 20, barSpacing: 3, zeroColor: '#b2b2b2'} );
							});
							$( "#tabs" ).tabs();
							// DATATABLE
							var lowtqtable = $('#dashboardkwdt').DataTable({
								"columnDefs": [
									{ "sortable": false, "targets": [11,12,13] },
									{						
										render: $.fn.dataTable.render.number( ',', '.', 2, '$' ),
										"targets": [7]
									}
								],
								"oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><b>Search&nbsp;&nbsp;</b><i class="fa fa-search"></i></a>' },
								dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
								"<'row'<'col-sm-12'tr>>" +
								"<'row'<'col-sm-5'" +
								"><'col-sm-7' <\"#actionSelectDiv\">> >" +
								"<'row'<'col-sm-5'i><'col-sm-7'p>>",
								buttons: [
									{
										extend:    'copyHtml5',
										text:      '<a href="#"><i class="fa fa-files-o"></i>&nbsp;Copy</a>',
										titleAttr: 'Copy'
									},
									{
										extend:    'csvHtml5',
										text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;CSV</a>',
										titleAttr: 'CSV'
									},
									{
									  extend:    'excel',
									  text:      '<a href="#"><i class="fa fa-file-text-o"></i>&nbsp;Excel</a>',
									  titleAttr: 'Excel'
									},
									{
										extend:    'pdfHtml5',
										text:      '<a href="#"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>',
										titleAttr: 'PDF'
									},
									{
										extend: 'print',
										text: '<a href="#"><i class="fa fa-print" aria-hidden="trPrint"></i>&nbsp;Print</a>',
										autoPrint: false
									}
									],
							 "footerCallback": function ( row, data, start, end, display ) {
								var api = this.api(), data;
								// start
								var sumImp = api
									.column( 1, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#kwtotal_imps").html(addCommas(sumImp));
								var sumRTClicks = api
									.column( 2 )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#kwtotal_rtclicks").html(addCommas(sumRTClicks));
								
								var sumRevClicks = api
									.column( 3, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#kwtotal_revclicks").html(addCommas(sumRevClicks));
								
								var sumCTR = 0;
								if (parseInt(sumImp) > 0 && parseInt(sumRTClicks) > 0) {
									sumCTR = parseInt(sumRTClicks) / parseInt(sumImp);
								} else {
									if (parseInt(sumImp) > 0 && parseInt(sumRevClicks) > 0) {
										sumCTR = parseInt(sumRevClicks) / parseInt(sumImp);
									} else {
										sumCTR = 0;
									}
								}
								sumCTR = sumCTR*100;
								sumCTR = sumCTR.toFixed(2);
								$("#kwtotal_ctr").html(sumCTR+"%");
								
								var sumRev = api
									.column( 7, {filter:'applied'} )
									.data()
									.reduce( function ( a, b ) {
										var x = parseFloat(a) || 0;
										var y = parseFloat(b) || 0;
										return x + y;
									} );
								$("#kwtotal_rev").html(addCommas("$"+sumRev.toFixed(2)));
								
								var sumRPI = 0.00;  // revenue / imp
								var sumRPC = 0.00;  // revenue / rev clicks
								
								if (parseInt(sumImp) > 0) {sumRPI = parseFloat(sumRev) / parseInt(sumImp); }
								if (parseInt(sumRevClicks) > 0) {sumRPC = parseFloat(sumRev) / parseInt(sumRevClicks); }
								
								$("#kwtotal_rpi").html(addCommas("$"+sumRPI.toFixed(2)));
								$("#kwtotal_rpc").html(addCommas("$"+sumRPC.toFixed(2)));
								
								
								// end
							 }
							});					
							// END DATATABLE
							// LOAD AUTOWEIGHTER
							$.ajax({
								method: "GET",
								url: "/getautoweight",
								data: {'rgid' : $("#kwrgid").val()}
							  })
								.done(function( msg ) {
								  if (msg == "1") {
									$( "#autoweighter" ).prop( "checked", true );
								  } else {
									$( "#autoweighter" ).prop( "checked", false );
								  }
								});
							// END WEIGHTER
							
							KW_drawActionElements();
							$("#allweight").hide();
							var HRPCLoader = window.setInterval(LoadingHistoricRPC, 200);
								// GET SUGGEST
								$.ajax({
									url : "/json/getKeywordSuggestionsJSON",
									type: "GET",
									data : 'rev_group_id=' + $("#kwrgid").val(),
									success:function(data, textStatus, jqXHR)
									{
										var innerData = "<table style='width:100%;'>";
										var idx = 0;
										for (var i in data) {
											if(idx % 3 == 0){
												innerData = innerData + "<tr>";
											}
											innerData = innerData + '<td style="padding: 0px 0px 0px 6px;vertical-align: bottom;">'
												+ '<input class="addSuggest"  id="addSuggest_' + idx + '" type="checkbox" value="'
												+ data[i].keyword + '"><label for="addSuggest_' + idx + '"><span style="vertical-align: top;">'
												+ '&nbsp;&nbsp;' + '$' + data[i].rpc + '&nbsp;&nbsp;' +  data[i].keyword
												+ '</span></label></td>';
											idx++;
											if(idx % 3 == 0){
												innerData = innerData + "</tr>";
											}

										}
										innerData = innerData + "</table>";
										clearInterval(HRPCLoader);
										if($.isEmptyObject(data)){
											$("#suggestedKeywordsListDiv").html('No keyword suggestions');
										} else {
											$("#suggestedKeywordsListDiv").html(innerData);
										}
										$('#addHistoricKeywordsButton').show();
										$('#keywordSuggestionButton').hide();
									},
									error: function(jqXHR, textStatus, errorThrown)
									{
										alert("Error in get keyword suggest ajax contact dev team with this message.");
									}
								});
								// END SUGGEST
							$("#rgnotes_list").load("/getRevGroupNotesList?rev_group_id="+$("#kwrgid").val());
						
					});
				} else {
					window.location = "/login";
				}
			});
		
	}
	
	function updateautoweight() {
    if ($("#autoweighter").is(':checked')) {
        autoweight = 1;
    } else {
        autoweight = 0;
    }

    var rgid = $('#kwrgid').val();
    $.ajax({
      method: "GET",
      url: "/updateautoweight",
      data: {'rgid' : rgid, 'autoweight' : autoweight}
    })
      .done(function( msg ) {
        
      });
  }

  function updateweight(plid,rgid,kw,res) {
    var weight = $("#kwdrweight"+plid+" :selected").val();
    if (weight != '-1') {
      $.ajax({
        method: "GET",
        url: "/updateweight",
        data: {'plid' : plid,'weight' : weight,'rgid' : rgid, 'kw' : kw, 'resolution_id' : res}
      })
        .done(function( msg ) {
          $( "#autoweighter" ).prop( "checked", false );
        });
      }
  }
  function updatelimit(plid,rgid,kw) {
    var limit = $("#kwdrlimit"+plid+" :selected").val();

    $.ajax({
      method: "GET",
      url: "/updatelimit",
      data: {'plid' : plid,'limit' : limit,'rgid' : rgid, 'kw' : kw}
    })
      .done(function( msg ) {
        $( "#autoweighter" ).prop( "checked", false );
      });

  }
  
  function KW_drawActionElements() {
    var divContent = '<select name="keywordActionSelect" id="keywordActionSelect" class="form-control input-sm">'
        + '<option value="none">Select Action to apply</option>'
        + '<option value="setActive">Set Selected Active</option>'
        + '<option value="setInactive">Set Selected Inactive</option>'
        + '<option value="resetSelected">Reset Selected</option>'
        + '<option value="disableRetest">Disable retest</option>'
        + '<option value="changeWeight">Change Weights</option>'
        + '</select>&nbsp;&nbsp;'
        + '<select id="allweight"  class="form-control input-sm"><option value="100">100</option><option value="95">95</option><option value="90">90</option><option value="85">85</option><option value="80">80</option><option value="75">75</option><option value="70">70</option><option value="65">65</option><option value="60">60</option><option value="55">55</option><option value="50">50</option><option value="45">45</option><option value="40">40</option><option value="35">35</option><option value="30">30</option><option value="25">25</option><option value="20">20</option><option value="15">15</option><option value="10" selected="">10</option><option value="5">5</option></select>'
        + '&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-xs" '
        + 'name="buttonActionSelect" onclick="KW_buttonclickKeywordApplyAction()" id="buttonActionSelect" style="margin-bottom: 10px;margin-top:5px;">Apply Action</button>';
    $("#actionSelectDiv").html(divContent);
    $("#actionSelectDiv").css("text-align","right");
}

  function KW_buttonclickKeywordApplyAction(){
    var idx = 0;
    var keywordsList = "";
    var value = $('#keywordActionSelect').val();

    var weight = $("#allweight :selected").val();

    if(value == "none"){
        return;
    }
    $(".keywordsCheckboxes").each(function (index) {

        if (this.checked) {
            if (idx > 0) {
                keywordsList = keywordsList + ",";
            }
            keywordsList = keywordsList + $(this).val();
            idx++;
        }
    });
    var url = "";
    if (value == "resetSelected") {
        url = "/json/resetMultipleKeywordUpdate";
    } else {
        if (value == "changeWeight") {
           url = "/json/updateMultipleKeywordUpdateWeight"; 
        } else {
            url = "/json/changeMultipleKeywordStatuses";
        }
    }
	
	//alert('keywordsList=' + keywordsList+ '&applyaction=' + value + "&weight=" + weight + '&_token=' + $("#myToken").val());
	$.ajax({
        url: url,
        type: "POST",
        data: 'keywordsList=' + keywordsList
        + '&applyaction=' + value + "&weight=" + weight
        + '&_token=' + $("#myToken").val(),
        success: function (data, textStatus, jqXHR) {
            //$('#KeywordsModal').modal('toggle');
            //location.reload(true);
			//alert(JSON.stringify(data));
			refreshKWPanel();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            //
        }
		});

	}
	
	function KW_addkeywords(){
        if($("#use_spellcheck").val() == 0){
            var resolution = $("#kwresolution").val();
            var rev_group_id = $('#kwrgid').val();
            var keyword = $('#addrevgroupform_keyword').val();
            var defaultweight = $('#kwweight').val();
            var defaultlimit = $('#kwlimit').val();

            //keyword = keyword.split("'").join('');
            var lines = $('#addrevgroupform_keyword').val().split('\n');
            for(var i = 0;i < lines.length;i++){
                if(lines[i].length < 3 && lines[i].length > 0){
                    $("#modalKeyword3CharacterAlert").show();
                    lines.splice(i,1);
                    return false;
                } else {
                    $("#modalKeyword3CharacterAlert").hide();
                }
            }
            var keyword = $('#addrevgroupform_keyword').val();
            var _token = $('#myToken').val();
            //prompt("test",'addKeywordsToRevGroupFormPost?rev_group_id=' + rev_group_id + '&keyword=' + encodeURIComponent(keyword) + '&resolution=' + resolution + '&defaultweight=' + defaultweight + '&defaultlimit=' + defaultlimit  + '&_token=' + _token);
            $.ajax(
                {
                    url : "addKeywordsToRevGroupFormPost",
                    type: "POST",
                    data : 'rev_group_id=' + rev_group_id
                    + '&keyword=' + encodeURIComponent(keyword) + '&resolution=' + resolution + '&defaultweight=' + defaultweight + '&defaultlimit=' + defaultlimit + '&_token=' + _token,
                    success:function(data, textStatus, jqXHR)
                    {
                        refreshKWPanel();
                        $('#addrevgroupform_keyword').val("");
                    },
                });
        } else {
            var newA = {};
            var resolution = $("#kwresolution").val();
            var rev_group_id = $('#kwrgid').val();
            var keyword = $('#addrevgroupform_keyword').val();
            //keyword = keyword.split("'").join('');
            var lines = $('#addrevgroupform_keyword').val().split('\n');
            var _token = $('#myToken').val();
            $.ajax({
                url : "json/spellCheckText",
                type: "POST",
                data : 'rev_group_id=' + rev_group_id
                + '&keyword=' + encodeURIComponent(keyword) + '&resolution=' + resolution
                + '&_token=' + _token,
                success:function(data, textStatus, jqXHR)
                {
                    if(typeof(data) != 'object'){
                        returnData = JSON.parse(data);
                    } else {
                        returnData = data;
                    }
                    var hasSpellingErrors = false;
                    var lines = returnData.HighlightedTextLines.length;
                    for(var idx = 0;idx < lines;idx++){
                        if(returnData.HighlightedTextLines[idx] != ""){
                            hasSpellingErrors = true;
                        }
                    }
                    if(hasSpellingErrors){
                        $('#spellCheckModal').modal()
                        var arrayLength = returnData.HighlightedTextLines.length;

                        var innerHtmlErrorsText = '';
                        for(var idx = 0;idx < arrayLength;idx++){

                            if(returnData.HighlightedTextLines[idx] != ""){
                                innerHtmlErrorsText += returnData.HighlightedTextLines[idx] + "<br/>";
                            }
                        }
                        $("#textErrorsDiv").html(innerHtmlErrorsText);
                        // Now we have the error in the text box.  Lets start correcting them....
                        // Get first misspelled word by the class there should only be one
                        var suggestions = returnData.BadSpelling[$(".wordmisspelled").html()];
                        var option = '';
                        $("#spellingSuggestList").find('option').remove();
                        for (var i=0;i<suggestions.length;i++){
                            option += '<option value="'+ suggestions[i] + '">' + suggestions[i] + '</option>';
                        }
                        $('#spellingSuggestList').append(option);
                        var firstItemValue = $('#spellingSuggestList').find("option:first-child").val();
                        $("#correctionWord").val(firstItemValue);
                    } else {
                        var newA = {};
                        var resolution = $("#resolution_idKeywordsModal").val();
                        var rev_group_id = $('#viewKeyWordsRevGroup').val();
                        var keyword = $('#addrevgroupform_keyword').val();
                        //keyword = keyword.split("'").join('');
                        var lines = $('#addrevgroupform_keyword').val().split('\n');
                        for(var i = 0;i < lines.length;i++){
                            if(lines[i].length < 3 && lines[i].length > 0){
                                $("#modalKeyword3CharacterAlert").show();
                                lines.splice(i,1);
                                return false;
                            } else {
                                $("#modalKeyword3CharacterAlert").hide();
                            }
                        }
               
                        var keyword = $('#addrevgroupform_keyword').val();
                        var _token = $('#myToken').val();
                        $.ajax(
                            {
                                url : "addKeywordsToRevGroupFormPost",
                                type: "POST",
                                data : 'rev_group_id=' + rev_group_id
                                + '&keyword=' + encodeURIComponent(keyword) + '&resolution=' + resolution
                                + '&_token=' + _token,
                                success:function(data, textStatus, jqXHR)
                                {
                                    refreshKWPanel();
                                    $('#addrevgroupform_keyword').val("");
                                },
                            });
                    }
                },
            });
        }
    }

    function addNotes() {
		var rev_group_id = $('#kwrgid').val();
		var notes = $('#rgnotes').val().replace(/'/g,"&apos;");
		var _token = $('#myToken').val();


		if(notes != "") {
			$.ajax(
					{
						url : "addNotesToRevGroupFormPost",
						type: "POST",
						data : 'rev_group_id=' + rev_group_id
								+ '&notes=' + encodeURIComponent(notes)
								+ '&_token=' + _token,
						success:function(data, textStatus, jqXHR)
						{
							//refreshKeywordsDatatable();
							//alert(data);
							$("#rgnotes_list").load("/getRevGroupNotesList?rev_group_id="+rev_group_id);
							$('#rgnotes').val("");
						},
						error: function(jqXHR, textStatus, errorThrown)
						{
							alert("Error in get keyword suggest ajax contact dev team with this message."+errorThrown);
						}
					});
		}

	}
  </script>
@stop