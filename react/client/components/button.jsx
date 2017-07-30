import React from 'react';


export default class Button extends React.Component {

  render(){
    var classes = this.props.color;
    if(!this.props.active){
      classes += ' disabled';
    }
    return (
      <button className={classes} onClick={this.props.onClick.bind(this)}>
        {this.props.children}
      </button>
    );
  }
}

Button.defaultProps = {
  color: '',
  active: true,
  onClick: function(){
    console.log('clicked');
  }
}