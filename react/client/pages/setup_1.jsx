import React from 'react';
import {observer} from 'mobx-react';
import {observable} from 'mobx';
import {Redirect} from 'react-router';
import axios from 'axios';
import Button from '../components/button.jsx';
import Panel from '../components/panel.jsx';
import PanelTitle from '../components/panelTitle.jsx';
import PanelContent from '../components/panelContent.jsx';
import PanelDown from '../components/panelDown.jsx';
import Row from '../components/row.jsx';

@observer
export default class Setup_1 extends React.Component {

  @observable redirect = false;
  @observable data = {
    url:      'http://localhost/Race/',
    host:     'localhost',
    database: '',
    username: '',
    password: ''
  };
  @observable nextButton   = 'Daten überprüfen';
  @observable dataSaved    = false;
  @observable okMessage    = '';
  @observable errorMessage = '';


  next(){
    if(this.dataSaved){
      this.redirect = '/setup/2';
    } else {
      this.sendData();
    }
  }

  prev(){
    this.redirect = '/';
  }

  sendData(){
    var self = this;
    this.props.store.url = this.data.url;
    axios.post(this.props.store.url+'setup/mysql', this.data)
    .then(function(r){
      console.log(r);
      if(r.data.status == 'OK'){
        console.log('MySQL credentials were correct');
        self.okMessage  = 'Die Anmeldedaten funktionieren :)';
        self.errorMessage = '';
        self.nextButton = 'Weiter';
        self.dataSaved  = true;
      }
    })
    .catch(function(error){
      console.log(error);
      self.okMessage = '';
      self.errorMessage = 'Es konnte leider keine Verbindung zum Server hergestellt werden :(';
    });
  }

  ci(e){
    if(e.target.name in this.data){
      this.data[e.target.name] = e.target.value;
    }
  }

  render(){
    var r = null;
    var d = this.data;
    if(this.redirect){
      r = <Redirect push to={this.redirect}/>
    }
    return (
      <Panel>
        {r}
        <PanelTitle step="1/3">Setup</PanelTitle>
        <PanelContent>
          <p>Bitte geben Sie die Verbindungsdaten ein:</p>
          <Row>
            <p>Server-URL:</p>
            <input onChange={this.ci.bind(this)} name="url" value={d.url}></input>
          </Row>
          <Row>
            <p>Host:</p>
            <input onChange={this.ci.bind(this)} name="host" value={d.host}></input>
          </Row>
          <Row>
            <p>Username:</p>
            <input onChange={this.ci.bind(this)} name="username" value={d.username}></input>
          </Row>
          <Row>
            <p>Password:</p>
            <input onChange={this.ci.bind(this)} name="password" type="password" value={d.password}></input>
          </Row>
          <Row>
            <p>Database:</p>
            <input onChange={this.ci.bind(this)} name="database" value={d.database}></input>
          </Row>
          <p className="error">{this.errorMessage}</p>
          <p className="ok">{this.okMessage}</p>
        </PanelContent>
        <PanelDown>
          <Button color="red" onClick={this.prev.bind(this)}>Abbrechen</Button>
          <Button color="blue" onClick={this.next.bind(this)}>{this.nextButton}</Button>
        </PanelDown>
      </Panel>
    );
  }
}