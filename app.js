var p1 = 50; //パラメータの設定
var p2 = 200; //パラメータの設定
var datanum = data_xarray.length; //データの個数

//原データの指数平滑移動平均値を返す関数
function EMACalc(mArray, mRange) {
  var k = 2 / (mRange + 1);
  // first item is just the same as the first item in the input
  emaArray = [mArray[0]];
  // for the rest of the items, they are computed with the previous one
  for (var i = 1; i < mArray.length; i++) {
    emaArray.push(mArray[i] * k + emaArray[i - 1] * (1 - k));
  }
  return emaArray;
}

//分析対象のデータ群を設定する関数
var getDataSet = function () {
  var data1 = data_yarray;
  var data2 = EMACalc(data1, p1);
  var data3 = EMACalc(data1, p2);

  return [data1, data2, data3];
};

//静止グラフ作成
async function getGraph_S() {
  var dataArrs = getDataSet();
  var d1 = dataArrs[0];
  var d2 = dataArrs[1];
  var d3 = dataArrs[2];

  var n1 = dataname.slice(0, -4);
  var n2 = `EMA(${p1})`;
  var n3 = `EMA(${p2})`;

  await Plotly.plot("chart", [
    {
      x: data_xarray,
      y: d1,
      name: n1,
      line: { width: 1, color: "black" },
    },
    { x: data_xarray, y: d2, name: n2, line: { width: 1, color: "blue" } },
    { x: data_xarray, y: d3, name: n3, line: { width: 1, color: "red" } },
  ]);
}

getGraph_S();
