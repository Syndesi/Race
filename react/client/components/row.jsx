import React from 'react';


export default class Row extends React.Component {

  render(){
    var classes = 'row ' + this.props.className;
    return (
      <div className={classes}>
        {this.props.children}
      </div>
    );
  }
}

Row.defaultProps = {
  className: ''
}