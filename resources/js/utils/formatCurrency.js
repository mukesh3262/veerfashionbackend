export function formatCurrency(amount, currency = "INR", locale = "en-IN") {
    if (amount == null || amount === "") return "";
  
    return new Intl.NumberFormat(locale, {
      style: "currency",
      currency,
    }).format(amount);
  }