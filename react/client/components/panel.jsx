import React from 'react';


export default class Panel extends React.Component {

  render(){
    return (
      <div className="panel popup">
        {this.props.children}
      </div>
    );
  }
}