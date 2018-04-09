package{
	import flash.display.MovieClip;
	import flash.display.StageAlign;
	import flash.display.StageScaleMode;
	import flash.events.Event;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.net.URLRequestHeader;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	
	public class Main extends MovieClip{
		public function Main():void { addEventListener(Event.ADDED_TO_STAGE, init); }
		private function init(e:Event):void {
			removeEventListener(Event.ADDED_TO_STAGE, init);
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.align = StageAlign.TOP_LEFT;
			// Initial code
				var variables:URLVariables = new URLVariables();
					variables.table = "cu_api_keys";
					variables.method = "consult";
				var header:URLRequestHeader = new URLRequestHeader("key", "yX3ReNsPdELUCaeFONbMpa8hyKlXk889");
				var request:URLRequest = new URLRequest("https://int-server-tree.com/cuppa_test/administrator/api/");
					request.data = variables;
					request.method = URLRequestMethod.POST;
					request.requestHeaders.push(header);			
				var loader:URLLoader = new URLLoader();
					loader.addEventListener(Event.COMPLETE, completeHandler);
					loader.load(request);
		}
		private function completeHandler(e:Event):void {
			output.text = e.currentTarget.data;;
		}
	}
}