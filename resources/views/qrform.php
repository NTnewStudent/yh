<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Access-Control-Allow-Origin" content="*" />
		<!-- 在 head 标签中添加 meta 标签，并设置 viewport-fit=cover 值 -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, viewport-fit=cover">

		<!-- 开启 safe-area-inset-bottom 属性 -->
		<van-number-keyboard safe-area-inset-bottom />
		<!-- 引入样式文件 -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vant@2.2/lib/index.css">

		<!-- 引入 Vue 和 Vant 的 JS 文件 -->
		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/vant@2.2/lib/vant.min.js"></script>

		<!-- 地图定位 -->
		<script charset="utf-8" crossorigin="anonymous" src="https://map.qq.com/api/js?v=2.exp&key=BD4BZ-35K6W-VAVR7-RTNAL-IHFAO-6JB56"></script>
		<script src="https://3gimg.qq.com/lightmap/components/geolocation/geolocation.min.js"></script>
		<title>入住</title>
	</head>

	<body>

		<div id="app">
			<div class="content">
				<van-cell-group>
					<van-field v-model="formdata.contact_name" placeholder="请输入联系人姓名" required clearable label="联系人姓名" />
				</van-cell-group>
				<van-cell-group>
					<van-field v-model="formdata.phone" type="tel" placeholder="请输入手机号" label="手机号" required clearable />
				</van-cell-group>
				<van-cell-group>
					<van-field v-model="formdata.home_phone" type="tel" placeholder="请输入固定电话" label="固定电话" required clearable />
				</van-cell-group>
				<van-cell-group>
					<van-field v-model="formdata.company_name" type="text" placeholder="请输入公司地址" label="公司名称" required clearable />
				</van-cell-group>
				<van-cell-group>
					<van-field v-model="formdata.company_postion" type="text" label="公司地址" required clearable />
				</van-cell-group>

				<van-cell is-link @click="showPopup">公司经营范围 {{formdata.category_name}}</van-cell>
				<van-popup v-model="show" position="bottom" style="height: 30%;">
					<van-picker show-toolbar :columns="columns" @cancel="showPicker" @confirm="onConfirm" />
				</van-popup>

				<van-cell-group>
					<van-field v-model="formdata.postion" type="text" placeholder="点击打开地图选择公司位置" :label="action" required clearable
					 disabled @click="showMap" />
				</van-cell-group>
				<div id="maps" v-show="isMapShow"></div>
				<van-cell-group>
					<van-button type="primary" @click="submit">提交</van-button>
				</van-cell-group>
			</div>

		</div>
	</body>
	<script src="https://cdn.bootcss.com/axios/0.19.0-beta.1/axios.min.js"></script>
	<script>
		// 在 #app 标签下渲染一个按钮组件
		new Vue({
			el: '#app',
			data: {
				formdata: {
					contact_name: '',
					phone: '',
					home_phone: '',
					company_name: '',
					company_postion: '',
					category_name: '',
					category_id:'',
					info_desc: '',
					postion: '',
					jin: '',
					wei: ''
				},
				action: '公司坐标选择',
				isMapShow: false,
				showPicker: false,
				columns: ['土木', '水泥', '钢铁', '铁', '水管'],
				columnss:[
				{id:1,category_name:'土木'},
				{id:2,category_name:'水泥'},
				{id:3,category_name:'钢铁'},
				{id:4,category_name:'铁'},
				{id:5,category_name:'水管'},
				],
				show: false,
				map: '',
				mapLister: '',
				marker: '',
				currentPosition: '',
				center :''
			},
			methods: {
				submit:function(){
					axios({
						method:'post',
						url:'https://www.zoba.fun/client/public/index.php/User',
						data: this.formdata,

					}).then(data =>{
						if(data.data.code == 200){
							alert('添加成功！');
						//	清空数据
							this.formdata.contact_name = '';
							this.formdata.phone = '';
							this.formdata.home_phone = '';
							this.formdata.company_postion = '';
							this.formdata.company_name = '';
							this.formdata.category_name = '';
							this.formdata.category_id = '';
							this.formdata.postion = '';
							this.formdata.jin = '';
							this.formdata.wei = '';
						}else{
							alert('添加失败！');
						}
					}).catch(error => {
						alert("添加失败！");
					})

				},
				onConfirm: function(value,index) {
					console.log(value,index)
					this.formdata.category_name = this.columnss[index].category_name
					this.formdata.category_id   = this.columnss[index].id
					this.show = false
				},
				showPopup: function() {
					this.show = true;
				},
				//初始化地图函数  自定义函数名init
				showTeachMap: function() {
					var self = this
					self.center = new qq.maps.LatLng(39.916527, 116.397128)
					//定义map变量 调用 qq.maps.Map() 构造函数   获取地图显示容器
					this.map = new qq.maps.Map(document.getElementById('maps'), {
						center:self.center , // 地图的中心地理坐标。
						zoom: 18 ,// 地图的中心地理坐标。
					});

					//创建marker
					if (self.marker == null || self.marker == '') {
						self.marker = new qq.maps.Marker({
							position: self.center,
							map: self.map
						});
					}

					//定义监听
					//绑定单击事件添加参数
					this.mapLister = qq.maps.event.addListener(this.map, 'click', function(event) {

						self.formdata.postion = event.latLng.getLat() + ',' + event.latLng.getLng()
						self.formdata.jin = event.latLng.getLat()
						self.formdata.wei = event.latLng.getLng()
						self.marker.setPosition(event.latLng)
						self.map.setCenter(event.latLng)
						self.center = event.latlng

					});

					if(this.currentPosition == '' || this.currentPosition == null){
						this.getCurrent()
					}
				},
				showMap: function() {
					if (this.map == null || this.map == '') {
						this.showTeachMap();
					}
					this.isMapShow = !this.isMapShow
					this.isMapShow ? this.action = '点击关闭地图' : this.action = '公司坐标选择'
				},
				getCurrent: function() {
					var self = this
					this.currentPosition = new qq.maps.Geolocation('BD4BZ-35K6W-VAVR7-RTNAL-IHFAO-6JB56', 'yh-h5local')
					this.currentPosition.getLocation(function(e) {
						var currentCenter = new qq.maps.LatLng(e.lat, e.lng)
						self.marker.setPosition(currentCenter)
						self.center = currentCenter
						self.map.setCenter(currentCenter)
					}, function(e) {
						alert(JSON.stringify(e))
					})
				}
			},
			created: function() {

			},
			mounted: function() {
				//请求数据
				axios({
					method:'get',
					url:'https://www.zoba.fun/client/public/index.php/getAllCategory',
				}).then(data => {
					let column = [];
					if(data.data.code == 200){
					  console.log(data.data.msg)
						let datas = data.data.msg;
						for (let i =0;i<datas.length;i++){
							column.push(datas[i].category_name);
						}
						this.columns = column;
						this.columnss  = datas;
					}else{
						alert('获取数据失败!请检查网络');
					}
				}).catch(error => {
					alert('获取数据失败!请检查网络');
				})

			}
		});
	</script>
	<style>
		#app {
			display: flex;
			height: 100%;
			width: 100%;
			flex-direction: column;
			flex-wrap: wrap;
		}

		.content {
			margin-left: 12px;
			margin-right: 12px;
            margin-top: 12px;
		}

		#maps {
			width: 100%;
			height: 400px;
			display: block;
			position: relative;

		}
	</style>

</html>
