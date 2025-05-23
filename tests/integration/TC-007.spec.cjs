// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')


describe('TC007', function() {
this.timeout(30000)
let driver
let vars

beforeEach(async function() {
  driver = await new Builder().forBrowser('chrome').build()
  vars = {}
})
afterEach(async function() {
  await driver.quit();
})
  it('TC007', async function() {
    // Use leads table
{
  const element = await driver.findElement(By.css(".text-center:nth-child(2) #valueEditor > .text-center"))
  await driver.actions({ bridge: true}).doubleClick(element).perform()
}
await driver.findElement(By.css(".bg-\\[\\#3e3f45\\]")).sendKeys("Hello World!")
await driver.findElement(By.css(".bg-\\[\\#3e3f45\\]")).sendKeys("\\n")
await driver.wait(until.elementLocated(By.css(".mosha__toast__content__text")), 10000)
assert.equal(await driver.findElement(By.id("valueSpan")).getText(), "Hello World!")
  })
})
