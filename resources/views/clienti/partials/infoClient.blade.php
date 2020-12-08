complete : function (resp) {
console.log(resp.responseJSON);
$('#modalClient').html(resp.responseJSON[0].cognome+" "+resp.responseJSON[0].nome);
$('#ultima').html(resp.responseJSON[0].updated_at);

var primod = parseInt(resp.responseJSON[1]._250d);
var secondod = parseInt(resp.responseJSON[1]._500d);
var terzod = parseInt(resp.responseJSON[1]._1000d);
var quartod = parseInt(resp.responseJSON[1]._2000d);
var quintod = parseInt(resp.responseJSON[1]._4000d);
var sestod = parseInt(resp.responseJSON[1]._8000d);

var primos = parseInt(resp.responseJSON[1]._250s);
var secondos = parseInt(resp.responseJSON[1]._500s);
var terzos = parseInt(resp.responseJSON[1]._1000s);
var quartos = parseInt(resp.responseJSON[1]._2000s);
var quintos = parseInt(resp.responseJSON[1]._4000s);
var sestos = parseInt(resp.responseJSON[1]._8000s);

var chart = new CanvasJS.Chart("chartContainer", {
animationEnabled: true,
theme: "light2",
axisX:{
gridThickness: 1

},
axisY:{
includeZero: false,
reversed: true
},
width:470,
data: [{
type: "line",
name: "Destro",
dataPoints: [
{ y: primod, label: "250" },
{ y: secondod, label: "500"},
{ y: terzod, label: "1000" },
{ y: quartod, label: "2000" },
{ y: quintod, label: "4000" },
{ y: sestod, label: "8000" }
]
},
{
type: "line",
name: "Sinistro",
dataPoints: [
{ y: primos, label: "250" },
{ y: secondos, label: "500"},
{ y: terzos, label: "1000" },
{ y: quartos, label: "2000" },
{ y: quintos, label: "4000" },
{ y: sestos, label: "8000" }
]
}]
});
chart.render();

}