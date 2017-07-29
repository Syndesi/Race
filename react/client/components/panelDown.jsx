import React from 'react';


export default class PanelDown extends React.Component {

  render(){
    return (
      <div className="down">
        {this.props.children}
      </div>
    );
  }
}