import React from 'react';
import {observer} from 'mobx-react';
import Panel from '../components/panel.jsx';
import PanelTitle from '../components/panelTitle.jsx';
import PanelContent from '../components/panelContent.jsx';
import PanelDown from '../components/panelDown.jsx';
import ProgressBar from '../components/progressBar.jsx';

@observer
export default class Setup extends React.Component {

  render(){
    return (
      <Panel>
        <PanelTitle step="2/3">Setup</PanelTitle>
        <PanelContent>
          <p>Die Software importiert gerade alle Datensätze in die bereitgestellte MySQL-Datenbank.</p>
          <p key={this.props.store.s}>Seconds: {this.props.store.s}</p>
          <ProgressBar progress={this.props.store.s / 60} />
        </PanelContent>
        <PanelDown>
          <p>control elements</p>
        </PanelDown>
      </Panel>
    );
  }
}