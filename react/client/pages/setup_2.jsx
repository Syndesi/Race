import React from 'react';
import {observer} from 'mobx-react';
import {observable} from 'mobx';
import {Redirect} from 'react-router';
import axios from 'axios';
import Panel from '../components/panel.jsx';
import PanelTitle from '../components/panelTitle.jsx';
import PanelContent from '../components/panelContent.jsx';
import PanelDown from '../components/panelDown.jsx';
import Button from '../components/button.jsx';
import ProgressBar from '../components/progressBar.jsx';

@observer
export default class Setup_2 extends React.Component {

  @observable redirect = false;
  @observable progress = 0;
  @observable remaining = '60 min';
  @observable importStarted = false;

  componentDidMount(){
    //setInterval(this.checkImport.bind(this), 1000);
  }

  startImport(){
    var self = this;
    axios.get(this.props.store.url+'setup/import')
    .then(function(r){
      console.log(r);
      if(r.data.status == 'OK'){
        self.importStarted = true;
        console.log('importiert...');
      }
    })
    .catch(function(error){
      console.log(error);
    });
  }

  checkImport(){
    var self = this;
    axios.get(this.props.store.url+'setup/getProgress')
    .then(function(r){
      //console.log(r);
      if(r.data.status == 'OK'){
        //console.log('progress: '+r.data.result.progress);
        self.progress = r.data.result.progress;
        self.remaining = r.data.result.remaining;
        if(self.progress > 0){
          self.importStarted = true;
        }
      }
    })
    .catch(function(error){
      console.log(error);
    });
  }

  next(){
    if(this.importStarted){
      if(this.progress >= 100){
        this.redirect = '/setup/3';
      }
    } else {
      this.startImport();
    }
  }

  prev(){
    this.redirect = '/setup/1';
  }

  render(){
    var r = null;
    var p = null;
    if(this.redirect){
      r = <Redirect push to={this.redirect}/>
    }
    var nextButton = 'Importieren';
    var nextClass = 'blue';
    if(this.importStarted){
      nextButton = 'Weiter';
      nextClass = 'gray';
      if(this.progress >= 100){
        nextClass = 'blue';
      }
    }
    if(this.progress > 0){
      var p = <p>Fortschritt: {Math.round(this.progress * 100)} %<br/>Verbleibend: {this.remaining}</p>;
    }
    return (
      <Panel>
        {r}
        <PanelTitle step="2/3">Setup</PanelTitle>
        <PanelContent>
          <p>Die Datensätze werden nun in die bereitgestellte MySQL-Datenbank importiert, je nach Computer kann dies bis zu 2 Stunden dauern.</p>
          {p}
          <ProgressBar progress={this.progress}/>
        </PanelContent>
        <PanelDown>
          <Button color="red" onClick={this.prev.bind(this)}>Zurück</Button>
          <Button color={nextClass} onClick={this.next.bind(this)}>{nextButton}</Button>
        </PanelDown>
      </Panel>
    );
  }
}