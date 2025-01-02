@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Monitoring Realtime'])
    <div class="row">
        <div class="col-lg-12 col-sm-12" id="main-svg"></div>
    </div>
    {{-- <div class="row mt-1 px-1">
        <div class="card">
            <div class="card-body px-1">
            </div>
        </div>
    </div> --}}
@endsection
@section('script')
    <script>
        $(function () {
            ConnectWsMonitoring();
        });
        function ConnectWsMonitoring() {
            let ws_url = $("input[name=ws_url]").val();

            var ws = new WebSocket(`${ws_url}/WS`);

            ws.onopen = function(event) {
                console.log('Connection Established');
            };

            $('#main-svg').html("");
            let svgExample = "{{ asset('assets/images/svg/AllLine.svg') }}";
            scadavisInit({
                container: 'main-svg',
                iframeparams: 'frameborder="0" height="1080" width="1920"',
                svgurl: svgExample
            }).then(sv => {
                sv.zoomTo(2.24);
                sv.enableTools(true, true);
                sv.hideWatermark();

                ws.onmessage = function(e) {
                    let data = JSON.parse(e.data);
                    // LINE 1
                    sv.storeValue("#L1Status", data.LineNumber1.DataPanel.status);
                    sv.storeValue("#L1Duration", data.LineNumber1.DataPanel.RunnningTime);
                    sv.storeValue("#L1OperatorName", data.LineNumber1.DataPanel.UserName);
                    sv.storeValue("#L1ProductionCode", data.LineNumber1.DataPanel.ProductionCode);
                    sv.storeValue("#L1T1Status", data.LineNumber1.Timbangan1.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L1T1Connection", data.LineNumber1.Timbangan1.StatusConnection_number);
                    sv.storeValue("#L1T1Error", data.LineNumber1.Timbangan1.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L1T1NumberUnder", data.LineNumber1.Timbangan1.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L1T1NumberGood", data.LineNumber1.Timbangan1.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L1T1NumberOver", data.LineNumber1.Timbangan1.NonRealTime.Excessivequantity);
                    sv.storeValue("#L1T1NumberError", data.LineNumber1.Timbangan1.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L1T1NumberTotal", data.LineNumber1.Timbangan1.NonRealTime.totalnumber);
                    sv.storeValue("#L1T1WeightUnder", data.LineNumber1.Timbangan1.NonRealTime.excessivelightweight);
                    sv.storeValue("#L1T1WeightGood", data.LineNumber1.Timbangan1.NonRealTime.eligibleweight);
                    sv.storeValue("#L1T1WeightOver", data.LineNumber1.Timbangan1.NonRealTime.excessiveweight);
                    sv.storeValue("#L1T1WeightTotal", data.LineNumber1.Timbangan1.NonRealTime.totalweight);
                    sv.storeValue("#L1T1AverageUnder", data.LineNumber1.Timbangan1.NonRealTime.Toolightaverage);
                    sv.storeValue("#L1T1AverageGood", data.LineNumber1.Timbangan1.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L1T1AverageOver", data.LineNumber1.Timbangan1.NonRealTime.Overweightaverage);
                    sv.storeValue("#L1T1PercentUnder", data.LineNumber1.Timbangan1.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L1T1PercentGood", data.LineNumber1.Timbangan1.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L1T1PercentOver", data.LineNumber1.Timbangan1.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L1T1PercentError", data.LineNumber1.Timbangan1.NonRealTime.Weighinganomaliespercentage);

                    sv.storeValue("#L1T2Status", data.LineNumber1.Timbangan2.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L1T2Connection", data.LineNumber1.Timbangan2.StatusConnection_number);
                    sv.storeValue("#L1T2Error", data.LineNumber1.Timbangan2.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L1T2NumberUnder", data.LineNumber1.Timbangan2.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L1T2NumberGood", data.LineNumber1.Timbangan2.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L1T2NumberOver", data.LineNumber1.Timbangan2.NonRealTime.Excessivequantity);
                    sv.storeValue("#L1T2NumberError", data.LineNumber1.Timbangan2.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L1T2NumberTotal", data.LineNumber1.Timbangan2.NonRealTime.totalnumber);
                    sv.storeValue("#L1T2WeightUnder", data.LineNumber1.Timbangan2.NonRealTime.excessivelightweight);
                    sv.storeValue("#L1T2WeightGood", data.LineNumber1.Timbangan2.NonRealTime.eligibleweight);
                    sv.storeValue("#L1T2WeightOver", data.LineNumber1.Timbangan2.NonRealTime.excessiveweight);
                    sv.storeValue("#L1T2WeightTotal", data.LineNumber1.Timbangan2.NonRealTime.totalweight);
                    sv.storeValue("#L1T2AverageUnder", data.LineNumber1.Timbangan2.NonRealTime.Toolightaverage);
                    sv.storeValue("#L1T2AverageGood", data.LineNumber1.Timbangan2.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L1T2AverageOver", data.LineNumber1.Timbangan2.NonRealTime.Overweightaverage);
                    sv.storeValue("#L1T2PercentUnder", data.LineNumber1.Timbangan2.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L1T2PercentGood", data.LineNumber1.Timbangan2.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L1T2PercentOver", data.LineNumber1.Timbangan2.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L1T2PercentError", data.LineNumber1.Timbangan2.NonRealTime.Weighinganomaliespercentage);

                    sv.storeValue("#L1T3Status", data.LineNumber1.Timbangan3.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L1T3Connection", data.LineNumber1.Timbangan3.StatusConnection_number);
                    sv.storeValue("#L1T3Error", data.LineNumber1.Timbangan3.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L1T3NumberUnder", data.LineNumber1.Timbangan3.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L1T3NumberGood", data.LineNumber1.Timbangan3.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L1T3NumberOver", data.LineNumber1.Timbangan3.NonRealTime.Excessivequantity);
                    sv.storeValue("#L1T3NumberError", data.LineNumber1.Timbangan3.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L1T3NumberTotal", data.LineNumber1.Timbangan3.NonRealTime.totalnumber);
                    sv.storeValue("#L1T3WeightUnder", data.LineNumber1.Timbangan3.NonRealTime.excessivelightweight);
                    sv.storeValue("#L1T3WeightGood", data.LineNumber1.Timbangan3.NonRealTime.eligibleweight);
                    sv.storeValue("#L1T3WeightOver", data.LineNumber1.Timbangan3.NonRealTime.excessiveweight);
                    sv.storeValue("#L1T3WeightTotal", data.LineNumber1.Timbangan3.NonRealTime.totalweight);
                    sv.storeValue("#L1T3AverageUnder", data.LineNumber1.Timbangan3.NonRealTime.Toolightaverage);
                    sv.storeValue("#L1T3AverageGood", data.LineNumber1.Timbangan3.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L1T3AverageOver", data.LineNumber1.Timbangan3.NonRealTime.Overweightaverage);
                    sv.storeValue("#L1T3PercentUnder", data.LineNumber1.Timbangan3.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L1T3PercentGood", data.LineNumber1.Timbangan3.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L1T3PercentOver", data.LineNumber1.Timbangan3.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L1T3PercentError", data.LineNumber1.Timbangan3.NonRealTime.Weighinganomaliespercentage);
                    // END LINE 1

                    // LINE 2
                    sv.storeValue("#L2Status", data.LineNumber2.DataPanel.status);
                    sv.storeValue("#L2Duration", data.LineNumber2.DataPanel.RunnningTime);
                    sv.storeValue("#L2OperatorName", data.LineNumber2.DataPanel.UserName);
                    sv.storeValue("#L2ProductionCode", data.LineNumber2.DataPanel.ProductionCode);
                    sv.storeValue("#L2T1Status", data.LineNumber2.Timbangan1.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L2T1Connection", data.LineNumber2.Timbangan1.StatusConnection_number);
                    sv.storeValue("#L2T1Error", data.LineNumber2.Timbangan1.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L2T1NumberUnder", data.LineNumber2.Timbangan1.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L2T1NumberGood", data.LineNumber2.Timbangan1.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L2T1NumberOver", data.LineNumber2.Timbangan1.NonRealTime.Excessivequantity);
                    sv.storeValue("#L2T1NumberError", data.LineNumber2.Timbangan1.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L2T1NumberTotal", data.LineNumber2.Timbangan1.NonRealTime.totalnumber);
                    sv.storeValue("#L2T1WeightUnder", data.LineNumber2.Timbangan1.NonRealTime.excessivelightweight);
                    sv.storeValue("#L2T1WeightGood", data.LineNumber2.Timbangan1.NonRealTime.eligibleweight);
                    sv.storeValue("#L2T1WeightOver", data.LineNumber2.Timbangan1.NonRealTime.excessiveweight);
                    sv.storeValue("#L2T1WeightTotal", data.LineNumber2.Timbangan1.NonRealTime.totalweight);
                    sv.storeValue("#L2T1AverageUnder", data.LineNumber2.Timbangan1.NonRealTime.Toolightaverage);
                    sv.storeValue("#L2T1AverageGood", data.LineNumber2.Timbangan1.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L2T1AverageOver", data.LineNumber2.Timbangan1.NonRealTime.Overweightaverage);
                    sv.storeValue("#L2T1PercentUnder", data.LineNumber2.Timbangan1.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L2T1PercentGood", data.LineNumber2.Timbangan1.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L2T1PercentOver", data.LineNumber2.Timbangan1.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L2T1PercentError", data.LineNumber2.Timbangan1.NonRealTime.Weighinganomaliespercentage);

                    sv.storeValue("#L2T2Status", data.LineNumber2.Timbangan2.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L2T2Connection", data.LineNumber2.Timbangan2.StatusConnection_number);
                    sv.storeValue("#L2T2Error", data.LineNumber2.Timbangan2.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L2T2NumberUnder", data.LineNumber2.Timbangan2.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L2T2NumberGood", data.LineNumber2.Timbangan2.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L2T2NumberOver", data.LineNumber2.Timbangan2.NonRealTime.Excessivequantity);
                    sv.storeValue("#L2T2NumberError", data.LineNumber2.Timbangan2.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L2T2NumberTotal", data.LineNumber2.Timbangan2.NonRealTime.totalnumber);
                    sv.storeValue("#L2T2WeightUnder", data.LineNumber2.Timbangan2.NonRealTime.excessivelightweight);
                    sv.storeValue("#L2T2WeightGood", data.LineNumber2.Timbangan2.NonRealTime.eligibleweight);
                    sv.storeValue("#L2T2WeightOver", data.LineNumber2.Timbangan2.NonRealTime.excessiveweight);
                    sv.storeValue("#L2T2WeightTotal", data.LineNumber2.Timbangan2.NonRealTime.totalweight);
                    sv.storeValue("#L2T2AverageUnder", data.LineNumber2.Timbangan2.NonRealTime.Toolightaverage);
                    sv.storeValue("#L2T2AverageGood", data.LineNumber2.Timbangan2.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L2T2AverageOver", data.LineNumber2.Timbangan2.NonRealTime.Overweightaverage);
                    sv.storeValue("#L2T2PercentUnder", data.LineNumber2.Timbangan2.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L2T2PercentGood", data.LineNumber2.Timbangan2.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L2T2PercentOver", data.LineNumber2.Timbangan2.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L2T2PercentError", data.LineNumber2.Timbangan2.NonRealTime.Weighinganomaliespercentage);

                    sv.storeValue("#L2T3Status", data.LineNumber2.Timbangan3.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L2T3Connection", data.LineNumber2.Timbangan3.StatusConnection_number);
                    sv.storeValue("#L2T3Error", data.LineNumber2.Timbangan3.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L2T3NumberUnder", data.LineNumber2.Timbangan3.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L2T3NumberGood", data.LineNumber2.Timbangan3.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L2T3NumberOver", data.LineNumber2.Timbangan3.NonRealTime.Excessivequantity);
                    sv.storeValue("#L2T3NumberError", data.LineNumber2.Timbangan3.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L2T3NumberTotal", data.LineNumber2.Timbangan3.NonRealTime.totalnumber);
                    sv.storeValue("#L2T3WeightUnder", data.LineNumber2.Timbangan3.NonRealTime.excessivelightweight);
                    sv.storeValue("#L2T3WeightGood", data.LineNumber2.Timbangan3.NonRealTime.eligibleweight);
                    sv.storeValue("#L2T3WeightOver", data.LineNumber2.Timbangan3.NonRealTime.excessiveweight);
                    sv.storeValue("#L2T3WeightTotal", data.LineNumber2.Timbangan3.NonRealTime.totalweight);
                    sv.storeValue("#L2T3AverageUnder", data.LineNumber2.Timbangan3.NonRealTime.Toolightaverage);
                    sv.storeValue("#L2T3AverageGood", data.LineNumber2.Timbangan3.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L2T3AverageOver", data.LineNumber2.Timbangan3.NonRealTime.Overweightaverage);
                    sv.storeValue("#L2T3PercentUnder", data.LineNumber2.Timbangan3.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L2T3PercentGood", data.LineNumber2.Timbangan3.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L2T3PercentOver", data.LineNumber2.Timbangan3.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L2T3PercentError", data.LineNumber2.Timbangan3.NonRealTime.Weighinganomaliespercentage);
                    // END LINE 2

                    // LINE 3
                    sv.storeValue("#L3Status", data.LineNumber3.DataPanel.status);
                    sv.storeValue("#L3Duration", data.LineNumber3.DataPanel.RunnningTime);
                    sv.storeValue("#L3OperatorName", data.LineNumber3.DataPanel.UserName);
                    sv.storeValue("#L3ProductionCode", data.LineNumber3.DataPanel.ProductionCode);
                    sv.storeValue("#L3T1Status", data.LineNumber3.Timbangan1.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L3T1Connection", data.LineNumber3.Timbangan1.StatusConnection_number);
                    sv.storeValue("#L3T1Error", data.LineNumber3.Timbangan1.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L3T1NumberUnder", data.LineNumber3.Timbangan1.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L3T1NumberGood", data.LineNumber3.Timbangan1.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L3T1NumberOver", data.LineNumber3.Timbangan1.NonRealTime.Excessivequantity);
                    sv.storeValue("#L3T1NumberError", data.LineNumber3.Timbangan1.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L3T1NumberTotal", data.LineNumber3.Timbangan1.NonRealTime.totalnumber);
                    sv.storeValue("#L3T1WeightUnder", data.LineNumber3.Timbangan1.NonRealTime.excessivelightweight);
                    sv.storeValue("#L3T1WeightGood", data.LineNumber3.Timbangan1.NonRealTime.eligibleweight);
                    sv.storeValue("#L3T1WeightOver", data.LineNumber3.Timbangan1.NonRealTime.excessiveweight);
                    sv.storeValue("#L3T1WeightTotal", data.LineNumber3.Timbangan1.NonRealTime.totalweight);
                    sv.storeValue("#L3T1AverageUnder", data.LineNumber3.Timbangan1.NonRealTime.Toolightaverage);
                    sv.storeValue("#L3T1AverageGood", data.LineNumber3.Timbangan1.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L3T1AverageOver", data.LineNumber3.Timbangan1.NonRealTime.Overweightaverage);
                    sv.storeValue("#L3T1PercentUnder", data.LineNumber3.Timbangan1.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L3T1PercentGood", data.LineNumber3.Timbangan1.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L3T1PercentOver", data.LineNumber3.Timbangan1.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L3T1PercentError", data.LineNumber3.Timbangan1.NonRealTime.Weighinganomaliespercentage);

                    sv.storeValue("#L3T2Status", data.LineNumber3.Timbangan2.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L3T2Connection", data.LineNumber3.Timbangan2.StatusConnection_number);
                    sv.storeValue("#L3T2Error", data.LineNumber3.Timbangan2.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L3T2NumberUnder", data.LineNumber3.Timbangan2.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L3T2NumberGood", data.LineNumber3.Timbangan2.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L3T2NumberOver", data.LineNumber3.Timbangan2.NonRealTime.Excessivequantity);
                    sv.storeValue("#L3T2NumberError", data.LineNumber3.Timbangan2.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L3T2NumberTotal", data.LineNumber3.Timbangan2.NonRealTime.totalnumber);
                    sv.storeValue("#L3T2WeightUnder", data.LineNumber3.Timbangan2.NonRealTime.excessivelightweight);
                    sv.storeValue("#L3T2WeightGood", data.LineNumber3.Timbangan2.NonRealTime.eligibleweight);
                    sv.storeValue("#L3T2WeightOver", data.LineNumber3.Timbangan2.NonRealTime.excessiveweight);
                    sv.storeValue("#L3T2WeightTotal", data.LineNumber3.Timbangan2.NonRealTime.totalweight);
                    sv.storeValue("#L3T2AverageUnder", data.LineNumber3.Timbangan2.NonRealTime.Toolightaverage);
                    sv.storeValue("#L3T2AverageGood", data.LineNumber3.Timbangan2.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L3T2AverageOver", data.LineNumber3.Timbangan2.NonRealTime.Overweightaverage);
                    sv.storeValue("#L3T2PercentUnder", data.LineNumber3.Timbangan2.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L3T2PercentGood", data.LineNumber3.Timbangan2.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L3T2PercentOver", data.LineNumber3.Timbangan2.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L3T2PercentError", data.LineNumber3.Timbangan2.NonRealTime.Weighinganomaliespercentage);

                    sv.storeValue("#L3T3Status", data.LineNumber3.Timbangan3.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L3T3Connection", data.LineNumber3.Timbangan3.StatusConnection_number);
                    sv.storeValue("#L3T3Error", data.LineNumber3.Timbangan3.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L3T3NumberUnder", data.LineNumber3.Timbangan3.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L3T3NumberGood", data.LineNumber3.Timbangan3.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L3T3NumberOver", data.LineNumber3.Timbangan3.NonRealTime.Excessivequantity);
                    sv.storeValue("#L3T3NumberError", data.LineNumber3.Timbangan3.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L3T3NumberTotal", data.LineNumber3.Timbangan3.NonRealTime.totalnumber);
                    sv.storeValue("#L3T3WeightUnder", data.LineNumber3.Timbangan3.NonRealTime.excessivelightweight);
                    sv.storeValue("#L3T3WeightGood", data.LineNumber3.Timbangan3.NonRealTime.eligibleweight);
                    sv.storeValue("#L3T3WeightOver", data.LineNumber3.Timbangan3.NonRealTime.excessiveweight);
                    sv.storeValue("#L3T3WeightTotal", data.LineNumber3.Timbangan3.NonRealTime.totalweight);
                    sv.storeValue("#L3T3AverageUnder", data.LineNumber3.Timbangan3.NonRealTime.Toolightaverage);
                    sv.storeValue("#L3T3AverageGood", data.LineNumber3.Timbangan3.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L3T3AverageOver", data.LineNumber3.Timbangan3.NonRealTime.Overweightaverage);
                    sv.storeValue("#L3T3PercentUnder", data.LineNumber3.Timbangan3.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L3T3PercentGood", data.LineNumber3.Timbangan3.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L3T3PercentOver", data.LineNumber3.Timbangan3.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L3T3PercentError", data.LineNumber3.Timbangan3.NonRealTime.Weighinganomaliespercentage);
                    // END LINE 3

                    // LINE 4
                    sv.storeValue("#L4Status", data.LineNumber4.DataPanel.status);
                    sv.storeValue("#L4Duration", data.LineNumber4.DataPanel.RunnningTime);
                    sv.storeValue("#L4OperatorName", data.LineNumber4.DataPanel.UserName);
                    sv.storeValue("#L4ProductionCode", data.LineNumber4.DataPanel.ProductionCode);
                    sv.storeValue("#L4T1Status", data.LineNumber4.Timbangan1.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L4T1Connection", data.LineNumber4.Timbangan1.StatusConnection_number);
                    sv.storeValue("#L4T1Error", data.LineNumber4.Timbangan1.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L4T1NumberUnder", data.LineNumber4.Timbangan1.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L4T1NumberGood", data.LineNumber4.Timbangan1.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L4T1NumberOver", data.LineNumber4.Timbangan1.NonRealTime.Excessivequantity);
                    sv.storeValue("#L4T1NumberError", data.LineNumber4.Timbangan1.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L4T1NumberTotal", data.LineNumber4.Timbangan1.NonRealTime.totalnumber);
                    sv.storeValue("#L4T1WeightUnder", data.LineNumber4.Timbangan1.NonRealTime.excessivelightweight);
                    sv.storeValue("#L4T1WeightGood", data.LineNumber4.Timbangan1.NonRealTime.eligibleweight);
                    sv.storeValue("#L4T1WeightOver", data.LineNumber4.Timbangan1.NonRealTime.excessiveweight);
                    sv.storeValue("#L4T1WeightTotal", data.LineNumber4.Timbangan1.NonRealTime.totalweight);
                    sv.storeValue("#L4T1AverageUnder", data.LineNumber4.Timbangan1.NonRealTime.Toolightaverage);
                    sv.storeValue("#L4T1AverageGood", data.LineNumber4.Timbangan1.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L4T1AverageOver", data.LineNumber4.Timbangan1.NonRealTime.Overweightaverage);
                    sv.storeValue("#L4T1PercentUnder", data.LineNumber4.Timbangan1.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L4T1PercentGood", data.LineNumber4.Timbangan1.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L4T1PercentOver", data.LineNumber4.Timbangan1.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L4T1PercentError", data.LineNumber4.Timbangan1.NonRealTime.Weighinganomaliespercentage);

                    sv.storeValue("#L4T2Status", data.LineNumber4.Timbangan2.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L4T2Connection", data.LineNumber4.Timbangan2.StatusConnection_number);
                    sv.storeValue("#L4T2Error", data.LineNumber4.Timbangan2.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L4T2NumberUnder", data.LineNumber4.Timbangan2.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L4T2NumberGood", data.LineNumber4.Timbangan2.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L4T2NumberOver", data.LineNumber4.Timbangan2.NonRealTime.Excessivequantity);
                    sv.storeValue("#L4T2NumberError", data.LineNumber4.Timbangan2.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L4T2NumberTotal", data.LineNumber4.Timbangan2.NonRealTime.totalnumber);
                    sv.storeValue("#L4T2WeightUnder", data.LineNumber4.Timbangan2.NonRealTime.excessivelightweight);
                    sv.storeValue("#L4T2WeightGood", data.LineNumber4.Timbangan2.NonRealTime.eligibleweight);
                    sv.storeValue("#L4T2WeightOver", data.LineNumber4.Timbangan2.NonRealTime.excessiveweight);
                    sv.storeValue("#L4T2WeightTotal", data.LineNumber4.Timbangan2.NonRealTime.totalweight);
                    sv.storeValue("#L4T2AverageUnder", data.LineNumber4.Timbangan2.NonRealTime.Toolightaverage);
                    sv.storeValue("#L4T2AverageGood", data.LineNumber4.Timbangan2.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L4T2AverageOver", data.LineNumber4.Timbangan2.NonRealTime.Overweightaverage);
                    sv.storeValue("#L4T2PercentUnder", data.LineNumber4.Timbangan2.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L4T2PercentGood", data.LineNumber4.Timbangan2.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L4T2PercentOver", data.LineNumber4.Timbangan2.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L4T2PercentError", data.LineNumber4.Timbangan2.NonRealTime.Weighinganomaliespercentage);

                    sv.storeValue("#L4T3Status", data.LineNumber4.Timbangan3.NonRealTime.WeighingMachineStatus.StopStart);
                    sv.storeValue("#L4T3Connection", data.LineNumber4.Timbangan3.StatusConnection_number);
                    sv.storeValue("#L4T3Error", data.LineNumber4.Timbangan3.NonRealTime.WeighingMachineStatus.Failure);
                    sv.storeValue("#L4T3NumberUnder", data.LineNumber4.Timbangan3.NonRealTime.Excessivenumberoflighterweights);
                    sv.storeValue("#L4T3NumberGood", data.LineNumber4.Timbangan3.NonRealTime.Numberofqualifications);
                    sv.storeValue("#L4T3NumberOver", data.LineNumber4.Timbangan3.NonRealTime.Excessivequantity);
                    sv.storeValue("#L4T3NumberError", data.LineNumber4.Timbangan3.NonRealTime.Weighinganomalies);
                    sv.storeValue("#L4T3NumberTotal", data.LineNumber4.Timbangan3.NonRealTime.totalnumber);
                    sv.storeValue("#L4T3WeightUnder", data.LineNumber4.Timbangan3.NonRealTime.excessivelightweight);
                    sv.storeValue("#L4T3WeightGood", data.LineNumber4.Timbangan3.NonRealTime.eligibleweight);
                    sv.storeValue("#L4T3WeightOver", data.LineNumber4.Timbangan3.NonRealTime.excessiveweight);
                    sv.storeValue("#L4T3WeightTotal", data.LineNumber4.Timbangan3.NonRealTime.totalweight);
                    sv.storeValue("#L4T3AverageUnder", data.LineNumber4.Timbangan3.NonRealTime.Toolightaverage);
                    sv.storeValue("#L4T3AverageGood", data.LineNumber4.Timbangan3.NonRealTime.Qualifiedaverage);
                    sv.storeValue("#L4T3AverageOver", data.LineNumber4.Timbangan3.NonRealTime.Overweightaverage);
                    sv.storeValue("#L4T3PercentUnder", data.LineNumber4.Timbangan3.NonRealTime.Toolightpercentage);
                    sv.storeValue("#L4T3PercentGood", data.LineNumber4.Timbangan3.NonRealTime.Qualifiedpercentage);
                    sv.storeValue("#L4T3PercentOver", data.LineNumber4.Timbangan3.NonRealTime.Overweightpercentage);
                    sv.storeValue("#L4T3PercentError", data.LineNumber4.Timbangan3.NonRealTime.Weighinganomaliespercentage);
                    // END LINE 4
                    sv.updateValues();
                }
            });


            ws.onclose = function() {
                // connection closed, discard old websocket and create a new one in 5s
                ws = null
                setTimeout(() => {
                    console.log('Attempting to reconnect...');
                    $('#main-svg').html("");
                    ConnectWsMonitoring();
                }, 5000);
            }
        }
    </script>
@endsection
