import {observable, action} from 'mobx';
import axios from 'axios';
import _ from 'lodash';

class Store {

  @observable apiURL = "http://localhost/mango5777/api/";
  @observable history = null;
  @observable s = 0;

  constructor(){
    console.log('loaded');
    setInterval(this.clock, 1000);
  }

  @action.bound
  clock(){
    var d = new Date();
    this.s = d.getSeconds();
    console.log(this.s);
  }

  loadLang(code){
    var self = this;
    axios.get(self.apiURL+'lang/get&lang='+code)
    .then(function(res){
      if(res.data.status == 'OK'){
        self.lang = res.data.result;
        console.log('Language ['+res.data.result.code+'] loaded.');
        console.log(self.getL('registerDescription'));
      }
    })
    .catch(function(res){
      console.log('Language couldn´t be loaded.');
    });
  }
}

var store = window.s = new Store();
export default store;