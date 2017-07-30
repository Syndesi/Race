import React from 'react';
import {observer} from 'mobx-react';
import {observable} from 'mobx';
import {Redirect} from 'react-router';
import Panel from '../components/panel.jsx';
import PanelTitle from '../components/panelTitle.jsx';
import PanelContent from '../components/panelContent.jsx';
import PanelDown from '../components/panelDown.jsx';
import Button from '../components/button.jsx';

@observer
export default class Setup_3 extends React.Component {

  @observable redirect = false;

  next(){
    this.redirect = '/';
  }

  prev(){
    this.redirect = '/setup/2';
  }

  render(){
    var r = null;
    if(this.redirect){
      r = <Redirect push to={this.redirect}/>
    }
    return (
      <Panel>
        {r}
        <PanelTitle step="3/3">Setup</PanelTitle>
        <PanelContent>
          <p>Daten werden analysiert...</p>
        </PanelContent>
        <PanelDown>
          <Button color="red" onClick={this.prev.bind(this)}>Zurück</Button>
          <Button color="blue" onClick={this.next.bind(this)}>Weiter</Button>
        </PanelDown>
      </Panel>
    );
  }
}