import {observable, action} from 'mobx';
import axios from 'axios';
import _ from 'lodash';

class Store {

  @observable url = "http://localhost/Race/";
  @observable s = 0;

  constructor(){
    console.log('loaded');
    setInterval(this.clock, 1000);
  }

  @action.bound
  clock(){
    var d = new Date();
    this.s = d.getSeconds();
  }
}

var store = window.s = new Store();
export default store;