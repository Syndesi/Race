import React from 'react';


export default class PanelTitle extends React.Component {

  render(){
    return (
      <div className="title">
        <h1>{this.props.children}</h1>
        <p className="step">{this.props.step}</p>
      </div>
    );
  }
}