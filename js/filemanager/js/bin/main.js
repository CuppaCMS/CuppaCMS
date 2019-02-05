window.PATH_CU_FM = "PATH_CU_FM";

class FileManager{
    constructor(){ }
    async fetch(action, data){
        data.action = action;
        data = JSON.stringify(data);
        let result = await fetch('api/index.php', {method: 'POST', headers: {"Content-Type": "application/json"}, body: data});
            result = await result.text();
            if(result) result = JSON.parse(result);
        return result;
    }
    reload(){
        cuppa.setData(PATH_CU_FM,{data: cuppa.getDataSync(PATH_CU_FM) });
    }
}
window.fileManager = new FileManager();

