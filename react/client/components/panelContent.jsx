import React from 'react';


export default class PanelContent extends React.Component {

  render(){
    return (
      <div className="content">
        {this.props.children}
      </div>
    );
  }
}