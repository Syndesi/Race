import React from 'react';
import {observer} from 'mobx-react';
import Radium from 'radium';

@observer
@Radium
export default class ProgressBar extends React.Component {

  render(){
    var style = {
      width: this.props.progress * 100 + '%'
    };
    return (
      <div className="progressBar">
        <div className="progress" style={style}></div>
      </div>
    );
  }
}