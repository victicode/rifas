const numberUtils = {
  numberFormat: (number) => {
    return new Intl.NumberFormat('de-DE').format(Number.parseFloat(number).toFixed(0))
  },
  numberFormatDecimal: (number) => {
    return new Intl.NumberFormat('de-DE').format(Number.parseFloat(number).toFixed(2))
  },
  moneyFormat: (number) => {
    let money = 0;

    if(number > 999999 && number < 1000000000){
        money = `${(number/1000000).toFixed(1)}mill` 
    }
    else if(number > 999 && number < 999999){
        money = `${(number/1000).toFixed(1)}mil` 
    }
    else{
        money = number
    }
    return money
  },
  moneyFormatSell: (number, decimals = 1) => {
    let money = 0;

    if(number > 999999 && number < 1000000000){
        money = `${(number/1000000).toFixed(decimals)} M` 
    }
    else if(number > 999 && number < 999999){
        money = `${(number/1000).toFixed(decimals)} K` 
    }
    else{
        money = number
    }
    return money
  }
}

export default {...numberUtils}