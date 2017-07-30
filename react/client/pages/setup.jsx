import React from 'react';
import {observer} from 'mobx-react';
import {observable} from 'mobx';
import {Redirect} from 'react-router';
import Panel from '../components/panel.jsx';
import PanelTitle from '../components/panelTitle.jsx';
import PanelContent from '../components/panelContent.jsx';
import PanelDown from '../components/panelDown.jsx';
import ProgressBar from '../components/progressBar.jsx';
import Button from '../components/button.jsx';

@observer
export default class Setup extends React.Component {

  @observable redirect = false;

  toMainPage(){
    if(this.props.store.s / 60 >= 0.5){
      console.log('redirect');
      this.redirect = true;
    }
  }

  render(){
    var s = this.props.store.s / 60;
    var color = '';
    var r = null;
    if(s >= 0.5){
      color = 'blue';
    }
    if(this.redirect){
      r = <Redirect push to="/"/>
    }
    return (
      <Panel>
        {r}
        <PanelTitle step="2/3">Setup</PanelTitle>
        <PanelContent>
          <p>Die Software importiert gerade alle Datensätze in die bereitgestellte MySQL-Datenbank.</p>
          <p key={this.props.store.s}>Seconds: {this.props.store.s}</p>
          <ProgressBar progress={s} />
        </PanelContent>
        <PanelDown>
          <Button color="red">Zurück</Button>
          <Button color={color} onClick={this.toMainPage.bind(this)}>Weiter</Button>
        </PanelDown>
      </Panel>
    );
  }
}